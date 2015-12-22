<h1><?php _e('Clear Recras cache', \Recras\Plugin::TEXT_DOMAIN); ?></h1>
<?php
    $subdomain = get_option('recras_subdomain');
?>

<p><?php _e('Data coming from your Recras (contact forms, arrangements, products) are cached for up to 24 hours. If you make important changes (i.e. a price increase) it is recommended you clear the corresponding cache.', \Recras\Plugin::TEXT_DOMAIN); ?></p>

<h2><?php _e('Arrangements', \Recras\Plugin::TEXT_DOMAIN); ?></h2>
<form action="<?= admin_url('admin-post.php?action=clear_arrangement_cache'); ?>" method="POST">
    <select name="arrangement">
<?php
    $arrangementModel = new \Recras\Arrangement;
    $arrangements = $arrangementModel->getArrangements($subdomain);
    $arrangements[0] = __('All', \Recras\Plugin::TEXT_DOMAIN);
    ksort($arrangements);

    foreach ($arrangements as $id => $arrangement) {
?>
        <option value="<?= $id; ?>"><?= $arrangement; ?>
<?php
    }
?>
    </select>
    <input type="submit" value="<?php _e('Clear', \Recras\Plugin::TEXT_DOMAIN); ?>">
</form>

<h2><?php _e('Contact forms', \Recras\Plugin::TEXT_DOMAIN); ?></h2>
<form action="<?= admin_url('admin-post.php?action=clear_contactform_cache'); ?>" method="POST">
    <select name="contactform">
<?php
    $formModel = new \Recras\ContactForm;
    $forms = $formModel->getForms($subdomain);
    $forms[0] = __('All', \Recras\Plugin::TEXT_DOMAIN);
    ksort($forms);

    foreach ($forms as $id => $form) {
?>
        <option value="<?= $id; ?>"><?= $form; ?>
<?php
    }
?>
    </select>
    <input type="submit" value="<?php _e('Clear', \Recras\Plugin::TEXT_DOMAIN); ?>">
</form>

<h2><?php _e('Products', \Recras\Plugin::TEXT_DOMAIN); ?></h2>
<form action="<?= admin_url('admin-post.php?action=clear_product_cache'); ?>" method="POST">
    <input type="hidden" name="product" value="0">
    <input type="submit" value="<?php _e('Clear all', \Recras\Plugin::TEXT_DOMAIN); ?>">
</form>
