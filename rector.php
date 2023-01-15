<?php

use Rector\CodingStyle\Rector\ArrowFunction\StaticArrowFunctionRector;
use Rector\CodingStyle\Rector\Closure\StaticClosureRector;
use Rector\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector;
use Rector\Set\ValueObject\SetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([
        SetList::CODING_STYLE,
        LevelSetList::UP_TO_PHP_81,
        SetList::CODE_QUALITY,
        SetList::TYPE_DECLARATION
    ]);

    $rectorConfig->paths([__DIR__ . '/src', __DIR__ . '/spec', __FILE__]);
    $rectorConfig->importNames();

    $rectorConfig->skip([
        NullToStrictStringFuncCallArgRector::class,
        StaticArrowFunctionRector::class => [
            __DIR__ . '/spec',
        ],
        StaticClosureRector::class => [
            __DIR__ . '/spec',
        ]
    ]);
};
