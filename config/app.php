<?php

return [
    'env' => defined('WP_ENV') && is_string(constant('WP_ENV')) ? constant('WP_ENV') : 'production',
    'email' => defined('RECOVERY_MODE_EMAIL') && is_email(constant('RECOVERY_MODE_EMAIL')) ? constant('RECOVERY_MODE_EMAIL') : get_option('admin_email'),
];
