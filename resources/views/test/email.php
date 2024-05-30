<p>
    <?php _e('Hi,', 'otomaties-health-check') ?>
</p>

<p>
    <?php _e('This is a test email to check if the website can send emails.', 'otomaties-health-check') ?><br/>
    <?php printf(__('This health check was triggered from %s by %s.', 'otomaties-health-check'), get_bloginfo('url'), $currentUser ? $currentUser->user_login : __('an unknown user', 'otomaties-health-check')) ?>
</p>

<p>
    <?php _e('Kind regards', 'otomaties-health-check') ?>
</p>
