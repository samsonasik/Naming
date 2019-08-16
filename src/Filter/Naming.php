<?php

declare(strict_types=1);

namespace Naming\Filter;

use Webmozart\Assert\Assert;
use Zend\Filter\AbstractFilter;
use Zend\Filter\StringTrim;
use Zend\Filter\StripTags;

class Naming extends AbstractFilter
{
    public function filter($value) : string
    {
        $value = (new StripTags())->filter($value);
        $value = (new StringTrim())->filter($value);
        $value = \mb_convert_case($value, \MB_CASE_TITLE, 'UTF-8');
        $value = \preg_replace('/\s{2,}/', ' ', $value);

        Assert::string($value);

        $chars = ['\'', '-'];
        \array_walk($chars, function ($row) use (& $value) {
            $position = \mb_strpos($value, $row);
            if ($position !== false && isset($value[$position + 1])) {
                $value[$position + 1] = \mb_strtoupper($value[$position + 1]);
            }
        });

        return $value;
    }
}
