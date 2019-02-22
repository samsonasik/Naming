<?php

declare(strict_types=1);

namespace Naming\Validator;

use Webmozart\Assert\Assert;
use Zend\Validator\AbstractValidator;

class Naming extends AbstractValidator
{
    private const SPECIAL                = 'SPECIAL';
    private const SINGLE_DOT             = 'SINGLE_DOT';
    private const SINGLE_HYPHEN          = 'SINGLE_HYPHEN';
    private const SINGLE_APOSTROPHE      = 'SINGLE_APOSTROPHE';
    private const CONSECUTIVE_DOT        = 'CONSECUTIVE_DOT';
    private const CONSECUTIVE_HYPHEN     = 'CONSECUTIVE_HYPHEN';
    private const CONSECUTIVE_APOSTROPHE = 'CONSECUTIVE_APOSTROPHE';
    private const DOT_TOBE_IN_LAST_WORD  = 'DOT_TOBE_IN_LAST_WORD';

    protected $messageTemplates = [
        self::SPECIAL                => 'Names can contain only letters, hyphens, apostrophe, spaces & full stops',
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

    public function isValid($value)
    {
        Assert::string($value);
        $this->setValue($value);

        $specs = \preg_match("/^[-. '\p{L}]+$/u", $value);
        if (! $specs) {
            $this->error(self::SPECIAL);
            return false;
        }

        $length = \mb_strlen($value);
        if ($length === 1) {
            $messageTemplates = [
                '.'  => self::SINGLE_DOT,
                '-'  => self::SINGLE_HYPHEN,
                '\'' => self::SINGLE_APOSTROPHE,
            ];

            if (\in_array($value, \array_keys($messageTemplates), true)) {
                $this->error($messageTemplates[$value]);
                return false;
            }
        } else {
            $messageTemplates = [
                '..'   => self::CONSECUTIVE_DOT,
                '--'   => self::CONSECUTIVE_HYPHEN,
                '\'\'' => self::CONSECUTIVE_APOSTROPHE,
            ];

            $error = \array_filter($messageTemplates, function ($key) use ($value) {
                return \strpos($value, $key) !== false;
            }, \ARRAY_FILTER_USE_KEY);

            if ($error) {
                $this->error(\current($error));
                return false;
            }
        }

        $specs = \preg_match("/(?:\.['\p{L}-]+|\s\.|^\.)/u", $value);
        if ($specs) {
            $this->error(self::DOT_TOBE_IN_LAST_WORD);
            return false;
        }

        return true;
    }
}
