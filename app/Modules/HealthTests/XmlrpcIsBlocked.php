<?php

namespace Otomaties\HealthCheck\Modules\HealthTests;

use Otomaties\HealthCheck\Enums\HealthCheckCategory;

class XmlrpcIsBlocked extends Abstracts\HealthTest implements Contracts\HealthTest
{
    protected string $category = HealthCheckCategory::SECURITY;

    protected string $type = 'async';

    public function passes() : bool
    {
        $response = wp_remote_get(
            site_url('xmlrpc.php'),
            ['sslverify' => otomatiesHealthCheck()->config('app.env') === 'production',]
        );
        return wp_remote_retrieve_response_code($response) === 403;
    }

    public function passedResponse() : array
    {
        return array_merge($this->defaultResponse, [
            'label' => __('Access to xmlrpc.php is blocked', 'otomaties-health-check'),
            'description' => sprintf(
                '<p>%s</p>',
                __('Access to xmlrpc.php is blocked', 'otomaties-health-check')
            ),
        ]);
    }

    public function failedResponse() : array
    {
        return array_merge($this->defaultResponse, [
            'label' => __('Access to xmlrpc.php is not blocked', 'otomaties-health-check'),
            'description' => sprintf(
                '<p>%s</p>',
                __('Add rules to block xmlrpc.php to your .htaccess file', 'otomaties-health-check')
            )
        ]);
    }
}
