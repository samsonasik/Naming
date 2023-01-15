<?php

declare(strict_types=1);

namespace Naming\Filter;

use Laminas\Filter\AbstractFilter;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Webmozart\Assert\Assert;

use function array_walk;
use function mb_convert_case;
use function mb_strpos;
use function mb_strtoupper;
use function preg_replace;

use const MB_CASE_TITLE;

final class Naming extends AbstractFilter
{
    /** @param string $value */
    public function filter($value): string
    {
        $value = (new StripTags())->filter($value);
        $value = (new StringTrim())->filter($value);
        $value = mb_convert_case((string) $value, MB_CASE_TITLE, 'UTF-8');
        $value = preg_replace('#\s{2,}#', ' ', $value);

        Assert::string($value);

        $chars = ["'", '-'];
        array_walk($chars, static function ($row) use (&$value): void {
            $position = mb_strpos($value, $row);
            if ($position !== false && isset($value[$position + 1])) {
                $value[$position + 1] = mb_strtoupper($value[$position + 1]);
            }
        });

        return $value;
    }
}
