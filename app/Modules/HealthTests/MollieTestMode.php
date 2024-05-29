<?php

namespace Otomaties\HealthCheck\Modules\HealthTests;

class MollieTestMode extends Abstracts\HealthTest implements Contracts\HealthTest
{
    public function name() : string
    {
        return 'mollie_test_mode';
    }

    public function category() : string
    {
        return __('System', 'otomaties-health-check');
    }

    public function type() : string
    {
        return 'direct';
    }

    public function passes() : bool
    {
        return !otomatiesHealthCheck()->make('env') === 'production' || get_option('mollie-payments-for-woocommerce_test_mode_enabled') !== 'yes';
    }

    public function active() : bool
    {
        if (!is_plugin_active('mollie-payments-for-woocommerce/mollie-payments-for-woocommerce.php'))
        {
            return false;
        }
        return parent::active();
    }

    public function passedResponse() : array
    {
        $label = otomatiesHealthCheck()->make('env') === 'production' ? __('Mollie is in Live mode', 'otomaties-health-check') : __('Mollie is in test mode but the environment is not production', 'otomaties-health-check');
        return array_merge($this->defaultResponse, [
            'label' => $label,
            'description' => sprintf(
                '<p>%s</p>',
                $label
            ),
        ]);
    }

    public function failedResponse() : array
    {
        return array_merge($this->defaultResponse, [
            'label' => __('Mollie is in test mode', 'otomaties-health-check'),
            'status' => 'critical',
            'description' => sprintf(
                '<p>%s</p>',
                __('Mollie is in test mode', 'otomaties-health-check')
            ),
            'actions' => sprintf('<a href="%s" target="_blank">%s</a>', admin_url('admin.php?page=wc-settings&tab=mollie_settings'), __('Disable test mode', 'otomaties-health-check'))
        ]);
    }
}
