<?php

namespace Otomaties\HealthCheck;

use Illuminate\Container\Container;
use Otomaties\HealthCheck\Helpers\Config;
use Otomaties\HealthCheck\Helpers\Loader;
use Otomaties\HealthCheck\Modules\HealthTests;
use Otomaties\HealthCheck\Modules\Notifier;
use Otomaties\HealthCheck\Modules\DisableBackgroundUpdatesTest;

class Plugin extends Container
{
    private array $modules = [
        HealthTests::class,
        Notifier::class,
        DisableBackgroundUpdatesTest::class,
    ];

    public function __construct(
        private Loader $loader,
        private Config $config
    ) {
    }

    public function config(string $key) : mixed
    {
        return $this->config->get($key);
    }

    public function initialize() : self
    {
        $this->loader->addAction('init', $this, 'loadTextDomain');
        $this->loadModules();
        return $this;
    }

    private function loadModules() : void
    {
        collect($this->modules)
            ->each(function ($className) {
                ($this->make($className))
                    ->init();
            });
    }

    public function loadTextDomain() : void
    {
        load_plugin_textdomain(
            'otomaties-health-check',
            false,
            basename($this->config('paths.base')) . '/resources/languages'
        );
    }

    public function getLoader() : Loader
    {
        return $this->loader;
    }

    public function runLoader() : void
    {
        apply_filters('otomaties_health_check_loader', $this->getLoader())
            ->run();
    }
}
