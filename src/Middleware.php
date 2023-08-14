<?php

declare(strict_types=1);

namespace Adedaramola\Zap;

abstract class Middleware
{
    abstract function execute(callable $next): void;
}
