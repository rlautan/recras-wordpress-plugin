<style>
    #TB_window #adminmenumain, #TB_window #wpfooter { display: none; }
    #TB_window #wpcontent { margin-left: 0; }
</style>
<?php
    $subdomain = get_option('recras_subdomain');

    $model = new \Recras\Arrangement;
    $arrangements = $model->getArrangements($subdomain);

    $model = new \Recras\ContactForm;
    $forms = $model->getForms($subdomain);
?>
<dl>
    <dt><label for="contactform_id"><?php _e('Contact form', \Recras\Plugin::TEXT_DOMAIN); ?></label>
        <dd><?php if (is_string($forms)) { ?>
            <input type="number" id="contactform_id" min="0" required>
            <?= $forms; ?>
        <?php } elseif(is_array($forms)) { ?>
            <select id="contactform_id" required>
                <?php foreach ($forms as $ID => $formName) { ?>
                <option value="<?= $ID; ?>"><?= $formName; ?>
                <?php } ?>
            </select>
        <?php } ?>
    <dt><label for="showtitle"><?php _e('Show title?', \Recras\Plugin::TEXT_DOMAIN); ?></label>
        <dd><input type="checkbox" id="showtitle" checked>
    <dt><label for="showlabels"><?php _e('Show labels?', \Recras\Plugin::TEXT_DOMAIN); ?></label>
        <dd><input type="checkbox" id="showlabels" checked>
    <dt><label for="showplaceholders"><?php _e('Show placeholders?', \Recras\Plugin::TEXT_DOMAIN); ?></label>
        <dd><input type="checkbox" id="showplaceholders" checked>
    <dt><label for="arrangement_id"><?php _e('Package', \Recras\Plugin::TEXT_DOMAIN); ?></label>
        <dd><?php if (is_string($arrangements)) { ?>
            <input type="number" id="arrangement_id" min="0" required>
            <?= $arrangements; ?>
        <?php } elseif(is_array($arrangements)) { ?>
            <select id="arrangement_id" required>
                <?php foreach ($arrangements as $ID => $arrangement) { ?>
                <option value="<?= $ID; ?>"><?= $arrangement->arrangement; ?>
                <?php } ?>
            </select>
        <?php } ?>
        <p class="recras-notice">
            <?php _e('Some packages may not be available for all contact forms. You can change this by editing your contact forms in Recras.', \Recras\Plugin::TEXT_DOMAIN); ?><br>
            <?php _e('If you are still missing packages, make sure "May be presented on a website (via API)" is enabled on the tab "Extra settings" of the package.', \Recras\Plugin::TEXT_DOMAIN); ?>
        </p>
    <dt><label for="container_element"><?php _e('HTML element', \Recras\Plugin::TEXT_DOMAIN); ?></label>
        <dd><select id="container_element">
                <option value="dl" selected><?php _e('Definition list', \Recras\Plugin::TEXT_DOMAIN); ?> (&lt;dl&gt;)
                <option value="ol"><?php _e('Ordered list', \Recras\Plugin::TEXT_DOMAIN); ?> (&lt;ol&gt;)
                <option value="table"><?php _e('Table', \Recras\Plugin::TEXT_DOMAIN); ?> (&lt;table&gt;)
            </select>
    <dt><label for="submit_text"><?php _e('Submit button text', \Recras\Plugin::TEXT_DOMAIN); ?></label>
        <dd><input type="text" id="submit_text" value="<?php _e('Send', \Recras\Plugin::TEXT_DOMAIN); ?>">
    <dt><label for="redirect_page"><?php _e('Redirect after submission', \Recras\Plugin::TEXT_DOMAIN); ?></label>
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
<button class="button button-primary" id="contact_submit"><?php _e('Insert shortcode', \Recras\Plugin::TEXT_DOMAIN); ?></button>

<script>
    // Check which arrangements are available
    getContactFormArrangements(document.getElementById('contactform_id').value, '<?php echo $subdomain; ?>');
    document.getElementById('contactform_id').addEventListener('change', function(){
        getContactFormArrangements(document.getElementById('contactform_id').value, '<?php echo $subdomain; ?>');
    });

    document.getElementById('contact_submit').addEventListener('click', function(){
        var shortcode = '[recras-contact id="' + document.getElementById('contactform_id').value + '"';

        var options = ['showtitle', 'showlabels', 'showplaceholders'];
        for (var i = 0; i < options.length; i++) {
            if (!document.getElementById(options[i]).checked) {
                shortcode += ' ' + options[i] + '="no"';
            }
        }

        if (document.getElementById('arrangement_id').value > 0) {
            shortcode += ' arrangement="' + document.getElementById('arrangement_id').value + '"';
        }
        if (document.getElementById('container_element').value !== 'dl') {
            shortcode += ' element="' + document.getElementById('container_element').value + '"';
        }
        if (document.getElementById('submit_text').value !== '<?php _e('Send', \Recras\Plugin::TEXT_DOMAIN); ?>') {
            shortcode += ' submittext="' + document.getElementById('submit_text').value + '"';
        }
        if (document.getElementById('redirect_page').value !== '') {
            shortcode += ' redirect="' + document.getElementById('redirect_page').value + '"';
        }

        shortcode += ']';

        tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
        tb_remove();
    });
</script>
