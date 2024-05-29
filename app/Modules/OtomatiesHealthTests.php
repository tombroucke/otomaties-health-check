<?php

namespace Otomaties\HealthCheck\Modules;

use Illuminate\Support\Collection;

class OtomatiesHealthTests extends Abstracts\Module
{
    private Collection $tests;

    public function init () {
        $this->loader->addFilter('site_status_tests', $this, 'addTests');
        $this->loader->addAction('rest_api_init', $this, 'addAsyncTestRoutes');
    }

    public function tests() : Collection
    {
        if (!isset($this->tests)) {
            $this->tests = collect(glob(__DIR__ . '/HealthTests/*.php'))
                ->map(function($file) {
                    $class = basename($file, '.php');
                    $namespace = 'Otomaties\\HealthCheck\\Modules\\HealthTests\\';
                    return new ($namespace . $class);
                })
                ->filter(function($test) {
                   return $test->active();
                })
                ->mapWithKeys(function($test, $key) {
                    return [$test->name() => [
                        'label' => $test->name(),
                        'test' => [$test, 'respond'],
                        'type' => $test->type(),
                    ]];
                });
        }
        return $this->tests;
    }

    public function addTests($tests) : array
    {
        $this->directTests()
            ->each(function ($test, $key) use (&$tests) {
                $tests['direct'][$key] = [
                    'label' => $test['label'],
                    'test' => $test['test'],
                ];
            });

        $this->asyncTests()
            ->each(function ($test, $key) use (&$tests) {
                $tests['async'][$key] = [
                    'label' => $test['label'],
                    'test' => rest_url(sprintf('otomaties-health-check/v1/tests/%s', $key)),
                    'has_rest' => true,
                    'async_direct_test' => $test['test'],
                ];
                add_action('wp_ajax_' . $key, $test['test']);
            });
        return $tests;
    }

    private function directTests() : Collection
    {
        return collect($this->tests())
        ->filter(function ($test) {
            return ($test['type'] ?? 'direct') === 'direct';
        });
    }

    private function asyncTests() : Collection
    {
        return collect($this->tests())
            ->filter(function ($test) {
                return ($test['type'] ?? 'direct') !== 'direct';
            });
    }

    public function addAsyncTestRoutes() : void
    {
        $this->asyncTests()
            ->each(function ($test, $key) {
                register_rest_route(
                    'otomaties-health-check/v1',
                    sprintf(
                        '/tests/%s',
                        $key
                    ),
                    [
                        'methods' => 'GET',
                        'callback' => $test['test'],
                        'permission_callback' => function () use ($key) {
                            return $this->validateRequestPermission($key);
                        },
                    ]
                );
            });
    }

    private function validateRequestPermission($check) : bool
    {
        $default_capability = 'view_site_health_checks';

        /**
         * Filters the capability needed to run a given Site Health check.
         *
         * @since 5.6.0
         *
         * @param string $default_capability The default capability required for this check.
         * @param string $check              The Site Health check being performed.
         */
        $capability = apply_filters("site_health_test_rest_capability_{$check}", $default_capability, $check);

        return current_user_can($capability);
    }
}
