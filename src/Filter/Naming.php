<?php

declare(strict_types=1);

namespace Naming\Filter;

use Laminas\Filter\FilterInterface;
use Laminas\Filter\StringTrim;
use Laminas\Filter\StripTags;
use Webmozart\Assert\Assert;

use function mb_convert_case;
use function mb_strlen;
use function mb_strtoupper;
use function preg_replace;
use function preg_replace_callback;
use function str_ends_with;

use const MB_CASE_TITLE;

final class Naming implements FilterInterface
{
    /**
     * @param string $value
     */
    public function filter(mixed $value): string
    {
        Assert::string($value);

        $value = (new StripTags())->filter($value);
        $value = (new StringTrim())->filter($value);

        $value = mb_convert_case($value, MB_CASE_TITLE, 'UTF-8');
        $value = (string) preg_replace('#\s{2,}#', ' ', $value);

        // Ensure the letter after apostrophe/hyphen is uppercase (Unicode-safe)
        $value = (string) preg_replace_callback(
            "/(?<=^|\s)(\p{L}+'?)(\p{Ll})/u",
            static function (array $matches): string {
                // only uppercase if it's single-letter prefix like D' or O'
                if (mb_strlen($matches[1], 'UTF-8') === 2 && str_ends_with($matches[1], "'")) {
                    return $matches[1] . mb_strtoupper($matches[2], 'UTF-8');
                }

                return $matches[1] . $matches[2];
            },
            $value
        );

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
