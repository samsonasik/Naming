<?php

declare(strict_types=1);

namespace Naming\Validator;

use Laminas\Validator\AbstractValidator;
use Webmozart\Assert\Assert;

use function array_filter;
use function array_key_exists;
use function current;
use function mb_strlen;
use function preg_match;
use function str_contains;

use const ARRAY_FILTER_USE_KEY;

final class Naming extends AbstractValidator
{
    /**
     * @var string
     */
    private const SPECIAL_OR_NUMBER = 'SPECIAL_OR_NUMBER';

    /**
     * @var string
     */
    private const SINGLE_DOT = 'SINGLE_DOT';

    /**
     * @var string
     */
    private const SINGLE_HYPHEN = 'SINGLE_HYPHEN';

    /**
     * @var string
     */
    private const SINGLE_APOSTROPHE = 'SINGLE_APOSTROPHE';

    /**
     * @var string
     */
    private const CONSECUTIVE_DOT = 'CONSECUTIVE_DOT';

    /**
     * @var string
     */
    private const CONSECUTIVE_HYPHEN = 'CONSECUTIVE_HYPHEN';

    /**
     * @var string
     */
    private const CONSECUTIVE_APOSTROPHE = 'CONSECUTIVE_APOSTROPHE';

    /**
     * @var string
     */
    private const DOT_TOBE_IN_LAST_WORD = 'DOT_TOBE_IN_LAST_WORD';

    /** @var array<string, string> */
    protected $messageTemplates = [
        self::SPECIAL_OR_NUMBER      => 'Names can contain only letters, hyphens, apostrophe, spaces & full stops',
        self::SINGLE_DOT             => 'Single "." character is not allowed',
        self::SINGLE_HYPHEN          => 'Single "-" character is not allowed',
        self::SINGLE_APOSTROPHE      => 'Single "\'" character is not allowed',
        self::CONSECUTIVE_DOT        => 'Consecutive "."s are not allowed',
        self::CONSECUTIVE_HYPHEN     => 'Consecutive "-"s are not allowed',
        self::CONSECUTIVE_APOSTROPHE => 'Consecutive "\'"s are not allowed',
        self::DOT_TOBE_IN_LAST_WORD  => '"." must be at last word character',
    ];

    /**
     * @param mixed[] $options
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);
    }

    /** @param string $value */
    public function isValid($value): bool
    {
        Assert::string($value);
        $this->setValue($value);

        $specs = preg_match("#^[-. '\p{L}]+$#u", $value);
        if (! $specs) {
            $this->error(self::SPECIAL_OR_NUMBER);
            return false;
        }

        $length = mb_strlen($value);
        if ($length === 1) {
            $messageTemplates = [
                '.' => self::SINGLE_DOT,
                '-' => self::SINGLE_HYPHEN,
                "'" => self::SINGLE_APOSTROPHE,
            ];

            if (array_key_exists($value, $messageTemplates)) {
                $this->error($messageTemplates[$value]);
                return false;
            }
        } else {
            $messageTemplates = [
                '..' => self::CONSECUTIVE_DOT,
                '--' => self::CONSECUTIVE_HYPHEN,
                "''" => self::CONSECUTIVE_APOSTROPHE,
            ];

            $error = array_filter(
                $messageTemplates,
                static fn($key): bool => str_contains($value, $key),
                ARRAY_FILTER_USE_KEY
            );

            if ($error !== []) {
                $this->error(current($error));
                return false;
            }
        }

        $specs = preg_match("#(?:\.['\p{L}-]+|\s\.|^\.)#u", $value);
        if ($specs) {
            $this->error(self::DOT_TOBE_IN_LAST_WORD);
            return false;
        }

        return true;
    }
}
