<?php
$subdomain = get_option('recras_subdomain');
if (!$subdomain) {
    \Recras\Settings::errorNoRecrasName();
    return;
}

$model = new \Recras\Vouchers;
$templates = $model->getTemplates($subdomain);
?>

<dl>
    <dt><label for="template_id"><?php _e('Template', \Recras\Plugin::TEXT_DOMAIN); ?></label>
    <dd><?php if (is_string($templates)) { ?>
            <input type="number" id="template_id" min="0">
            <?= $templates; ?>
        <?php } elseif(is_array($templates)) { ?>
            <select id="template_id" required>
                <option value="0"><?php _e('No pre-filled template', \Recras\Plugin::TEXT_DOMAIN); ?>
                <?php foreach ($templates as $ID => $template) { ?>
                <option value="<?= $ID; ?>"><?= $template->name; ?>
                <?php } ?>
            </select>
        <?php } ?>
    <dt><label for="redirect_page"><?php _e('Thank-you page', \Recras\Plugin::TEXT_DOMAIN); ?></label>
    <dd><select id="redirect_page">
            <option value=""><?php _e("Don't redirect", \Recras\Plugin::TEXT_DOMAIN); ?>
            <optgroup label="<?php _e('Pages', \Recras\Plugin::TEXT_DOMAIN); ?>">
                <?php foreach (get_pages() as $page) { ?>
                <option value="<?= get_permalink($page->ID); ?>"><?= htmlspecialchars($page->post_title); ?>
                <?php } ?>
            </optgroup>
            <optgroup label="<?php _e('Posts', \Recras\Plugin::TEXT_DOMAIN); ?>">
                <?php foreach (get_posts() as $post) { ?>
                <option value="<?= get_permalink($post->ID); ?>"><?= htmlspecialchars($post->post_title); ?>
                <?php } ?>
            </optgroup>
        </select>
</dl>
<button class="button button-primary" id="voucher_submit"><?php _e('Insert shortcode', \Recras\Plugin::TEXT_DOMAIN); ?></button>

<script>
    document.getElementById('voucher_submit').addEventListener('click', function(){
        var templateID = document.getElementById('template_id').value;
        var shortcode = '[<?= \Recras\Plugin::SHORTCODE_VOUCHER_SALES; ?>';

        if (templateID !== '0') {
            shortcode += ' id="' + templateID + '"';
        }

        if (document.getElementById('redirect_page').value !== '') {
            shortcode += ' redirect="' + document.getElementById('redirect_page').value + '"';
        }

        shortcode += ']';

        tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
        tb_remove();
    });
</script>
