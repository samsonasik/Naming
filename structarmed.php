<?php

declare(strict_types=1);

use Boundwize\StructArmed\Architecture;
use Boundwize\StructArmed\Preset\Preset;

return Architecture::define()
    ->withPresets(Preset::PSR4(), Preset::CODEQUALITY())
    ->layer('Filter', 'src/Filter')
    ->layer('Validator', 'src/Validator')
    ->ruleset([
        'Filter'    => [],
        'Validator' => [],
    ]);
