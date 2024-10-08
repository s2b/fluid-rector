<?php

declare(strict_types=1);

use Praetorius\FluidRector\ObjectBasedViewHelpersRector;
use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->rule(ObjectBasedViewHelpersRector::class);
};