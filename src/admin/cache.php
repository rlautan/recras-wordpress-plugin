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

<p><?php _e('Data coming from your Recras (contact forms, packages, products) is cached for up to 24 hours. If you make important changes (i.e. a price increase) it is recommended you clear the corresponding cache.', \Recras\Plugin::TEXT_DOMAIN); ?></p>
<hr>

<h2><?php _e('Packages', \Recras\Plugin::TEXT_DOMAIN); ?></h2>
<p><?php printf(__('This clears the cache for all packages, used in the %s shortcode.', \Recras\Plugin::TEXT_DOMAIN), '<code>[recras-package]</code>'); ?></p>
<form action="<?= admin_url('admin-post.php?action=clear_arrangement_cache'); ?>" method="POST">
    <input type="submit" value="<?php _e('Clear packages cache', \Recras\Plugin::TEXT_DOMAIN); ?>">
</form>
<hr>

<h2><?php _e('Contact forms', \Recras\Plugin::TEXT_DOMAIN); ?></h2>
<p><?php printf(__('This clears the cache for all contact forms, including fields and list of packages for each form. These are used in the %s shortcode.', \Recras\Plugin::TEXT_DOMAIN), '<code>[recras-contact]</code>'); ?></p>
<form action="<?= admin_url('admin-post.php?action=clear_contactform_cache'); ?>" method="POST">
    <input type="submit" value="<?php _e('Clear contact form cache', \Recras\Plugin::TEXT_DOMAIN); ?>">
</form>
<hr>

<h2><?php _e('Products', \Recras\Plugin::TEXT_DOMAIN); ?></h2>
<p><?php printf(__('This clears the cache for all products, used in the %s shortcode.', \Recras\Plugin::TEXT_DOMAIN), '<code>[recras-product]</code>'); ?></p>
<form action="<?= admin_url('admin-post.php?action=clear_product_cache'); ?>" method="POST">
    <input type="submit" value="<?php _e('Clear product cache', \Recras\Plugin::TEXT_DOMAIN); ?>">
</form>
