<?php

namespace Otomaties\HealthCheck\Modules\HealthTests;

use Otomaties\HealthCheck\Enums\HealthCheckCategory;

class WpRocketActivated extends Abstracts\HealthTest implements Contracts\HealthTest
{
    protected string $category = HealthCheckCategory::PERFORMANCE;

    public function passes() : bool
    {
        return is_plugin_active('wp-rocket/wp-rocket.php');
    }

    public function passedResponse() : array
    {
        return array_merge($this->defaultResponse, [
            'label' => __('WP Rocket is activated', 'otomaties-health-check'),
            'description' => sprintf(
                '<p>%s</p>',
                __('WP Rocket is installed and activated', 'otomaties-health-check')
            ),
        ]);
    }

    public function failedResponse() : array
    {
        return array_merge($this->defaultResponse, [
            'label' => __('WP Rocket is not activated', 'otomaties-health-check'),
            'description' => sprintf(
                '<p>%s</p>',
                sprintf(
                    __('WP Rocket is not active on this website. Visit %s for more information', 'otomaties-health-check'), // phpcs:ignore Generic.Files.LineLength.TooLong
                    '<a href="https://wp-rocket.me/" target="_blank">WP Rocket</a>'
                )
            ),
            'actions' => sprintf(
                '<a href="%s" target="_blank">%s</a>',
                admin_url('plugins.php'),
                __('Activate WP Rocket', 'otomaties-health-check')
            )
        ]);
    }
}
