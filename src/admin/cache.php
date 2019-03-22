<?php
    if (isset($_GET['msg'])) {
        //if ($_GET['msg'] === 'success') {
?>
<div class="updated notice">
    <p><?php _e('The selected cache was cleared.'); ?></p>
</div>
<?php
        /*} elseif ($_GET['msg'] === 'error') {
            ?>
<div class="error notice">
    <p><?php _e('The selected cache could not be cleared. This could be an error, or there could be nothing to clear.'); ?></p>
</div>
            <?php
        }*/
    }
?>

<h1><?php _e('Clear Recras cache', \Recras\Plugin::TEXT_DOMAIN); ?></h1>
<?php
    $subdomain = get_option('recras_subdomain');
?>

<p><?php _e('Data coming from your Recras (contact forms, packages, products, voucher templates) is cached for up to 24 hours. If you make important changes (i.e. a price increase) it is recommended you clear the corresponding cache.', \Recras\Plugin::TEXT_DOMAIN); ?></p>
<hr>

<h2><?php _e('Packages', \Recras\Plugin::TEXT_DOMAIN); ?></h2>
<p><?php printf(__('This clears the cache for all packages, also used for the availability calendar.', \Recras\Plugin::TEXT_DOMAIN)); ?></p>
<form action="<?= admin_url('admin-post.php?action=clear_arrangement_cache'); ?>" method="POST">
    <input type="submit" value="<?php _e('Clear packages cache', \Recras\Plugin::TEXT_DOMAIN); ?>">
</form>
<hr>

<h2><?php _e('Contact forms', \Recras\Plugin::TEXT_DOMAIN); ?></h2>
<p><?php printf(__('This clears the cache for all contact forms, including fields and list of packages for each form.', \Recras\Plugin::TEXT_DOMAIN)); ?></p>
<form action="<?= admin_url('admin-post.php?action=clear_contactform_cache'); ?>" method="POST">
    <input type="submit" value="<?php _e('Clear contact form cache', \Recras\Plugin::TEXT_DOMAIN); ?>">
</form>
<hr>

<h2><?php _e('Products', \Recras\Plugin::TEXT_DOMAIN); ?></h2>
<p><?php printf(__('This clears the cache for all products', \Recras\Plugin::TEXT_DOMAIN)); ?></p>
<form action="<?= admin_url('admin-post.php?action=clear_product_cache'); ?>" method="POST">
    <input type="submit" value="<?php _e('Clear product cache', \Recras\Plugin::TEXT_DOMAIN); ?>">
</form>
<hr>

<h2><?php _e('Voucher templates', \Recras\Plugin::TEXT_DOMAIN); ?></h2>
<p><?php printf(__('This clears the cache for all voucher templates', \Recras\Plugin::TEXT_DOMAIN)); ?></p>
<form action="<?= admin_url('admin-post.php?action=clear_voucher_template_cache'); ?>" method="POST">
    <input type="submit" value="<?php _e('Clear voucher templates cache', \Recras\Plugin::TEXT_DOMAIN); ?>">
</form>
