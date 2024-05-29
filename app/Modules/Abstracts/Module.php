<?php

namespace Otomaties\HealthCheck\Modules\Abstracts;

use Otomaties\HealthCheck\Helpers\Loader;

abstract class Module
{
    public function __construct(
        protected Loader $loader
    ) {
    }

    abstract public function init();
}
