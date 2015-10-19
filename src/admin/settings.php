<h1><?php _e('Recras settings', \Recras\Plugin::TEXT_DOMAIN); ?></h1>

<form action="options.php" method="POST">
<?php
    settings_fields('recras');
    do_settings_sections('recras');
    submit_button(__('Save', \Recras\Plugin::TEXT_DOMAIN));
?>
</form>
