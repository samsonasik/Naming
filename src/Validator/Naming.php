<?php

declare(strict_types=1);

namespace Naming\Validator;

use Laminas\Validator\AbstractValidator;
use Webmozart\Assert\Assert;

use function array_filter;
use function array_keys;
use function current;
use function in_array;
use function mb_strlen;
use function preg_match;
use function strpos;

use const ARRAY_FILTER_USE_KEY;

class Naming extends AbstractValidator
{
    public const SPECIAL_OR_NUMBER      = 'SPECIAL_OR_NUMBER';
    public const SINGLE_DOT             = 'SINGLE_DOT';
    public const SINGLE_HYPHEN          = 'SINGLE_HYPHEN';
    public const SINGLE_APOSTROPHE      = 'SINGLE_APOSTROPHE';
    public const CONSECUTIVE_DOT        = 'CONSECUTIVE_DOT';
    public const CONSECUTIVE_HYPHEN     = 'CONSECUTIVE_HYPHEN';
    public const CONSECUTIVE_APOSTROPHE = 'CONSECUTIVE_APOSTROPHE';
    public const DOT_TOBE_IN_LAST_WORD  = 'DOT_TOBE_IN_LAST_WORD';

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

    public function __construct(array $options = [])
    {
        parent::__construct($options);
    }

    /** @param string $value */
    public function isValid($value): bool
    {
        Assert::string($value);
        $this->setValue($value);

        $specs = preg_match("/^[-. '\p{L}]+$/u", $value);
        if (! $specs) {
            $this->error(self::SPECIAL_OR_NUMBER);
            return false;
        }

        $length = mb_strlen($value);
        if ($length === 1) {
            $messageTemplates = [
                '.'  => self::SINGLE_DOT,
                '-'  => self::SINGLE_HYPHEN,
                '\'' => self::SINGLE_APOSTROPHE,
            ];

            if (in_array($value, array_keys($messageTemplates), true)) {
                $this->error($messageTemplates[$value]);
                return false;
            }
        } else {
            $messageTemplates = [
                '..'   => self::CONSECUTIVE_DOT,
                '--'   => self::CONSECUTIVE_HYPHEN,
                '\'\'' => self::CONSECUTIVE_APOSTROPHE,
            ];

            $error = array_filter($messageTemplates, function ($key) use ($value) {
                return strpos($value, $key) !== false;
            }, ARRAY_FILTER_USE_KEY);

            if ($error) {
                $this->error(current($error));
                return false;
            }
        }

        $specs = preg_match("/(?:\.['\p{L}-]+|\s\.|^\.)/u", $value);
        if ($specs) {
            $this->error(self::DOT_TOBE_IN_LAST_WORD);
            return false;
        }

        return true;
    }
}
