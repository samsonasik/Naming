<?php

declare(strict_types=1);

namespace Naming\Filter;

use Laminas\Filter\FilterInterface;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Webmozart\Assert\Assert;

use function mb_convert_case;
use function mb_strtoupper;
use function preg_replace;
use function preg_replace_callback;

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

        // Ensure the letter after apostrophe/hyphen is uppercase (Unicode-safe)
        $value = preg_replace_callback(
            "/(['-])(\\p{L})/u",
            static fn(array $matches): string => $matches[1] . mb_strtoupper($matches[2], 'UTF-8'),
            (string) $value
        );

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
