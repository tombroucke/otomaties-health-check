<?php

return [
    'env' => defined('WP_ENV') && is_string(constant('WP_ENV')) ? constant('WP_ENV') : 'production',
];
