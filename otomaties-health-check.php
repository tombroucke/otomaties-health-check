<?php

use Otomaties\HealthCheck\Plugin;
use Otomaties\HealthCheck\Helpers\Config;
use Otomaties\HealthCheck\Helpers\Loader;

/**
 * Plugin Name:       Otomaties Health Check
 * Description:       Add a health check to check for common issues
 * Version:           1.0.0
 * Author:            Tom Broucke
 * Author URI:        https://tombroucke.be/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       otomaties-health-check
 * Domain Path:       /resources/languages
 */

if (!defined('ABSPATH')) {
    exit;
}

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once realpath(__DIR__ . '/vendor/autoload.php');
}

/**
 * Get main plugin class instance
 *
 * @return Plugin
 */
function otomatiesHealthCheck()
{
    static $plugin;

    if (!$plugin) {
        $plugin = new Plugin(
            new Loader(),
            new Config()
        );
        do_action('otomaties_health_check_functionality', $plugin);
    }

    return $plugin;
}

// Bind the class to the service container
add_action('otomaties_health_check_functionality', function ($plugin) {
    $plugin->bind(Loader::class, function ($plugin) {
        return $plugin->getLoader();
    });
    $plugin->bind('env', function () {
        return defined('WP_ENV') && is_string(constant('WP_ENV')) ? constant('WP_ENV') : 'production';
    });
}, 10);

// Initialize the plugin and run the loader
add_action('otomaties_health_check_functionality', function ($plugin) {
    $plugin
        ->initialize()
        ->runLoader();
}, 9999);

otomatiesHealthCheck();
