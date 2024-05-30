<p>
    <?php _e('Hi,', 'otomaties-health-check') ?>
</p>

<p>
    <?php printf(__('A health check has encountered critical errors on <a href="%s">%s</a>.', 'otomaties-health-check'), $homeUrl, $siteName); ?><br />
    <?php printf(__('Visit <a href="%s">Site Health</a> for more information.', 'otomaties-health-check'), $healthCheckUrl); ?>
</p>

<table>
    <?php foreach ($results as $severity => $quantity) : ?>
        <tr>
            <td><?php echo $severity ?></td>
            <td><?php echo $quantity ?></td>
        </tr>
    <?php endforeach; ?>
</table>

<p>
    <?php _e('Kind regards', 'otomaties-health-check') ?>
</p>
