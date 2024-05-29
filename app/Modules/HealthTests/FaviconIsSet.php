<?php

namespace Otomaties\HealthCheck\Modules\HealthTests;

class FaviconIsSet extends Abstracts\HealthTest implements Contracts\HealthTest
{
    public function name() : string
    {
        return 'favicon_is_set';
    }

    public function category() : string
    {
        return __('Appearance', 'otomaties-health-check');
    }

    public function type() : string
    {
        return 'direct';
    }

    public function passes() : bool
    {
        $favicon = get_site_icon_url();
        return ! empty($favicon);
    }

    public function passedResponse() : array
    {
        return array_merge($this->defaultResponse, [
            'label' => __('Favicon is set', 'otomaties-health-check'),
            'description' => sprintf(
                '<p>%s</p>',
                __('The favicon is set', 'otomaties-health-check')
            ),
        ]);
    }

    public function failedResponse() : array
    {
        return array_merge($this->defaultResponse, [
            'label' => __('Favicon is not set', 'otomaties-health-check'),
            'description' => sprintf(
                '<p>%s</p>',
                sprintf(
                    __('The favicon is not set on this website. Visit the %s to set your favicon', 'otomaties-health-check'),
                    '<a href="' . admin_url('customize.php') . '" target="_blank">' . __('Customizer', 'otomaties-health-check') . '</a>'
                )
            )
        ]);
    }
}
