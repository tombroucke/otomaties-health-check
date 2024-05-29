<?php

namespace Otomaties\HealthCheck\Modules\HealthTests;

class SecurityPluginActivated extends Abstracts\HealthTest implements Contracts\HealthTest
{
    public function name() : string
    {
        return 'wordfence_activated';
    }

    public function category() : string
    {
        return __('Security', 'otomaties-health-check');
    }

    public function type() : string
    {
        return 'direct';
    }

    public function passes() : bool
    {
        return is_plugin_active('sucuri-scanner/sucuri.php') || is_plugin_active('wordfence/wordfence.php') || is_plugin_active('wp-defender/wp-defender.php') || is_plugin_active('defender-security/wp-defender.php');
    }

    public function passedResponse() : array
    {
        return array_merge($this->defaultResponse, [
            'label' => __('Wordfence is activated', 'otomaties-health-check'),
            'status' => 'good',
            'description' => sprintf(
                '<p>%s</p>',
                __('Wordfence is installed and activated', 'otomaties-health-check')
            ),
        ]);
    }

    public function failedResponse() : array
    {
        return array_merge($this->defaultResponse, [
            'label' => __('Wordfence is not activated', 'otomaties-health-check'),
            'description' => sprintf(
                '<p>%s</p>',
                sprintf(
                    __('Wordfence is not active on this website. Visit %s for more information', 'otomaties-health-check'),
                    '<a href="https://www.wordfence.com/" target="_blank">Wordfence</a>'
                )
            ),
            'actions' => sprintf('<a href="%s" target="_blank">%s</a>', admin_url('plugins.php'), __('Activate Wordfence', 'otomaties-health-check'))
        ]);
    }
}
