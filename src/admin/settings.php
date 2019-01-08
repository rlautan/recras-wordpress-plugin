<?php
if (isset($_GET['msg']) && $_GET['msg'] === 'optinthanks') {
?>
    <div class="notice notice-success is-dismissible">
        <p><?php _e('Thanks for opting in to sharing statistics!', \Recras\Plugin::TEXT_DOMAIN); ?></p>
    </div>
<?php
}
?>
<h1><?php _e('Recras settings', \Recras\Plugin::TEXT_DOMAIN); ?></h1>

<form action="options.php" method="POST">
<?php
    settings_fields('recras');
    do_settings_sections('recras');
    submit_button(__('Save', \Recras\Plugin::TEXT_DOMAIN));
?>
</form>
