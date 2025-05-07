<?php

declare(strict_types=1);

namespace Naming\Filter;

use Laminas\Filter\FilterInterface;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Webmozart\Assert\Assert;

use function array_walk;
use function mb_convert_case;
use function mb_strpos;
use function mb_strtoupper;
use function preg_replace;

use const MB_CASE_TITLE;

final class Naming implements FilterInterface
{
    /**
     * @param string $value
     */
    public function filter(mixed $value): string
    {
        $value = (new StripTags())->filter($value);
        $value = (new StringTrim())->filter($value);

        Assert::string($value);

        $value = mb_convert_case($value, MB_CASE_TITLE, 'UTF-8');
        $value = preg_replace('#\s{2,}#', ' ', $value);

        $chars = ["'", '-'];
        array_walk($chars, static function ($row) use (&$value): void {
            Assert::string($value);

            $position = mb_strpos($value, $row);
            if ($position !== false && isset($value[$position + 1])) {
                $value[$position + 1] = mb_strtoupper($value[$position + 1]);
            }
        });

        Assert::string($value);
        return $value;
    }

    /**
     * @param string $value
     */
    public function __invoke(mixed $value): string
    {
        return $this->filter($value);
    }
}
