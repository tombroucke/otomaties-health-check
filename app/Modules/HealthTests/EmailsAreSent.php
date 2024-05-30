<?php

namespace Otomaties\HealthCheck\Modules\HealthTests;

use Otomaties\HealthCheck\Helpers\View;

class EmailsAreSent extends Abstracts\HealthTest implements Contracts\HealthTest
{
    protected string $type = 'async';

    public function passes() : bool
    {
        return wp_mail(
            to: 'tom@tombroucke.be',
            subject: sprintf(__('Health check: Test email from %s', 'otomaties-health-check'), get_bloginfo('name')),
            message: otomatiesHealthCheck()
                ->make(View::class)
                ->return('test/email', [
                    'currentUser' => wp_get_current_user()
                ]),
            headers: ['Content-Type: text/html; charset=UTF-8']
        );
    }

    public function active() : bool
    {
        if (wp_doing_cron()) {
            return false;
        }
        return parent::active();
    }

    public function passedResponse() : array
    {
        return array_merge($this->defaultResponse, [
            'label' => __('Website can send email', 'otomaties-health-check'),
            'description' => sprintf(
                '<p>%s</p>',
                __('The website can send emails', 'otomaties-health-check')
            )
        ]);
    }

    public function failedResponse() : array
    {
        return array_merge($this->defaultResponse, [
            'label' => __('Website can not send email', 'otomaties-health-check'),
            'status' => 'critical',
            'description' => sprintf(
                '<p>%s</p>',
                __('The website can not send emails. Check the email settings of the website', 'otomaties-health-check')
            )
        ]);
    }
}
