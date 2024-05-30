<?php

namespace Otomaties\HealthCheck\Modules\Abstracts;

use Otomaties\HealthCheck\Helpers\Loader;
use Otomaties\HealthCheck\Helpers\View;

abstract class Module
{
    public function __construct(
        protected Loader $loader,
        protected View $view,
    ) {
    }

    abstract public function init() : void;
}
