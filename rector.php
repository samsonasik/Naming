<?php

use Rector\CodingStyle\Rector\FuncCall\CallUserFuncArrayToVariadicRector;
use Rector\Core\Configuration\Option;
use Rector\Core\ValueObject\PhpVersion;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\ClassMethod\ReturnTypeFromStrictTypedPropertyRector;
use Rector\TypeDeclaration\Rector\Param\ParamTypeFromStrictTypedPropertyRector;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->import(SetList::CODING_STYLE);
    $containerConfigurator->import(SetList::PHP_70);
    $containerConfigurator->import(SetList::PHP_71);
    $containerConfigurator->import(SetList::PHP_72);
    $containerConfigurator->import(SetList::PHP_73);
    $containerConfigurator->import(SetList::CODE_QUALITY);
    $containerConfigurator->import(SetList::TYPE_DECLARATION);
    $containerConfigurator->import(SetList::TYPE_DECLARATION_STRICT);

    $parameters = $containerConfigurator->parameters();
    $parameters->set(Option::PATHS, [__DIR__ . '/src', __DIR__ . '/spec']);

    $parameters->set(Option::AUTO_IMPORT_NAMES, true);
    $parameters->set(Option::PHP_VERSION_FEATURES, PhpVersion::PHP_73);

    $parameters->set(Option::SKIP, [
        // requires php 7.4
        CallUserFuncArrayToVariadicRector::class,
        ReturnTypeFromStrictTypedPropertyRector::class,
        TypedPropertyFromStrictConstructorRector::class,
        ParamTypeFromStrictTypedPropertyRector::class,
    ]);
};
