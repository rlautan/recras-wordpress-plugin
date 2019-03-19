<?php
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'optinallow') {
?>
    <div class="notice notice-success is-dismissible">
        <p><?php _e('Thanks for opting in to sharing statistics!', \Recras\Plugin::TEXT_DOMAIN); ?></p>
    </div>
<?php
    } elseif ($_GET['msg'] === 'optindeny') {
        ?>
        <div class="notice notice-info is-dismissible">
            <p><?php _e('We\'re sorry to hear you don\'t want to share statistics, but we respect your decision. You can change your mind at any time using the setting below.', \Recras\Plugin::TEXT_DOMAIN); ?></p>
        </div>
        <?php
    }
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
