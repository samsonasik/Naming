<?php

use Rector\CodingStyle\Rector\Closure\StaticClosureRector;
use Rector\Php81\Rector\FuncCall\NullToStrictStringFuncCallArgRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withPreparedSets(codeQuality: true, codingStyle: true, typeDeclarations: true)
    ->withPhpSets(php81: true)
    ->withPaths([__DIR__ . '/src', __DIR__ . '/spec'])
    ->withRootFiles()
    ->withImportNames(removeUnusedImports: true)
    ->withSkip([
        NullToStrictStringFuncCallArgRector::class,
        StaticClosureRector::class => [
            __DIR__ . '/spec',
        ]
    ])
    ->withRules([
        StaticClosureRector::class,
    ]);
