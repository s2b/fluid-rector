<?php

use Praetorius\FluidRector\ObjectBasedViewHelpersRector;
use Rector\Config\RectorConfig;

return RectorConfig::configure()
    ->withSkipPath('vendor')
    // ->withPaths([
    //     __DIR__ . '/src',
    //     __DIR__ . '/tests',
    // ])
    // register single rule
    ->withRules([
        ObjectBasedViewHelpersRector::class,
    ])
;
