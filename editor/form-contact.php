<?php
    require_once('../../../../wp-load.php');
    //require_once('/var/www/html/wordpress/wp-load.php');

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
    <dt><label for="arrangement_id"><?php _e('Arrangement ID', \Recras\Plugin::TEXT_DOMAIN); ?></label>
        <dd><?php if (is_string($arrangements)) { ?>
            <input type="number" id="arrangement_id" min="0" required>
            <?= $arrangements; ?>
        <?php } elseif(is_array($arrangements)) { ?>
            <select id="arrangement_id" required>
                <?php foreach ($arrangements as $ID => $arrangement) { ?>
                <option value="<?= $ID; ?>"><?= $arrangement; ?>
                <?php } ?>
            </select>
        <?php } ?>
</dl>
<button class="button button-primary" id="contact_submit"><?php _e('Insert shortcode', \Recras\Plugin::TEXT_DOMAIN); ?></button>

<script>
    document.getElementById('contact_submit').addEventListener('click', function(){
        var shortcode = '[recras-contact id="' + document.getElementById('contactform_id').value + '"';
        if (!document.getElementById('showtitle').checked) {
            shortcode += ' showtitle="no"';
        }
        if (document.getElementById('arrangement_id').value > 0) {
            shortcode += ' arrangement="' + document.getElementById('arrangement_id').value + '"';
        }
        shortcode += ']';

        tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
        tb_remove();
    });
</script>
