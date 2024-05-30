<?php

namespace Otomaties\HealthCheck\Modules;

use Otomaties\HealthCheck\Helpers\View;

class Notifier extends Abstracts\Module
{
    public function init() : void
    {
        $this->loader->addAction('setted_transient', $this, 'notify', 10, 3);
    }

    public function notify($transient, $value, $expiration) : void
    {
        if ('health-check-site-status-result' !== $transient) {
            return;
        }
        
        $results = json_decode($value, true);
        if (0 == $results['critical']) {
            return;
        }
        
        $this->send($results);
    }

    public function send($results) : bool
    {
        return wp_mail(
            to: otomatiesHealthCheck()->config('app.email'),
            subject: sprintf(__('Critical errors found on %s', 'otomaties-health-check'), get_bloginfo('name')),
            message: otomatiesHealthCheck()->make(View::class)->return('notifications/critical-errors', [
                'results' => $results,
                'healthCheckUrl' => admin_url('site-health.php'),
                'homeUrl' => home_url(),
                'siteName' => get_bloginfo('name'),
            ]),
            headers: ['Content-Type: text/html; charset=UTF-8']
        );
    }
}
