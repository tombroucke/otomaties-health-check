<?php

namespace Otomaties\HealthCheck\Modules\HealthTests;

class EmailsAreSent extends Abstracts\HealthTest implements Contracts\HealthTest
{
    public function name() : string
    {
        return 'emails_are_sent';
    }

    public function category() : string
    {
        return __('Email', 'otomaties-health-check');
    }

    public function type() : string
    {
        return 'async';
    }

    public function passes() : bool
    {
        $to = 'tom@tombroucke.be';
        $subject = sprintf(__('Health check: Test email from %s', 'otomaties-health-check'), get_bloginfo('name'));
        $message = __('Hi,', 'otomaties-health-check') . "\n\n";
        $message .= __('This is a test email to check if the website can send emails.', 'otomaties-health-check') . "\n\n";
        $current_user = wp_get_current_user();
        $message .= sprintf(__('This health check was triggered from %s by %s.', 'otomaties-health-check'), get_bloginfo('url'), $current_user ? $current_user->user_login : __('an unknown user', 'otomaties-health-check')) . "\n\n";
        $message = __('Kind regards', 'otomaties-health-check');
        return wp_mail($to, $subject, $message);    
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
