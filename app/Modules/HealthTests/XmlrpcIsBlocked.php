<?php

namespace Otomaties\HealthCheck\Modules\HealthTests;

class XmlrpcIsBlocked extends Abstracts\HealthTest implements Contracts\HealthTest
{
    public function name() : string
    {
        return 'xmlrpc_is_blocked';
    }

    public function category() : string
    {
        return __('Security', 'otomaties-health-check');
    }

    public function type() : string
    {
        return 'async';
    }

    public function passes() : bool
    {
        $response = wp_remote_get(
            site_url('xmlrpc.php'),
            ['sslverify' => otomatiesHealthCheck()->make('env') === 'production',]
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
