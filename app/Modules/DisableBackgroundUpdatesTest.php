<?php

namespace Otomaties\HealthCheck\Modules;

class DisableBackgroundUpdatesTest extends Abstracts\Module
{
    public function init() : void
    {
        $this->loader->addFilter('site_status_test_result', $this, 'disableBackgroundUpdates');
        $this->loader->addFilter('rest_post_dispatch', $this, 'modifyRestResponse');
    }

    public function disableBackgroundUpdates(array $result) : array
    {
        if (! isset($result['test']) || $result['test'] !== 'background_updates') {
            return $result;
        }
    
        return $this->overrideTestBackgroundUpdates($result);
    }

    public function overrideTestBackgroundUpdates(array $result) : array
    {
        return array_replace($result, [
            'label'       => __('Background updates are disabled by Bedrock', 'otomaties-health-check'),
            'status'      => 'good',
            'description' => sprintf(
                '<p>%s</p><blockquote class="notice notice-info">%s</blockquote>',
                __('This site is under version control. Updates are managed by Composer.', 'otomaties-health-check'),
                $result['description']
            ),
        ]);
    }

    public function modifyRestResponse(\WP_HTTP_Response $result) : \WP_HTTP_Response
    {
        $data = $this->disableBackgroundUpdates($result->get_data());
        $result->set_data($data);
        return $result;
    }
}
