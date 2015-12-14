<style>
    #TB_window #adminmenumain, #TB_window #wpfooter { display: none; }
    #TB_window #wpcontent { margin-left: 0; }
</style>
<?php
    $subdomain = get_option('recras_subdomain');

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
</dl>
<button class="button button-primary" id="booking_submit"><?php _e('Insert shortcode', \Recras\Plugin::TEXT_DOMAIN); ?></button>

<script>
    document.getElementById('booking_submit').addEventListener('click', function(){
        var shortcode = '[recras-booking id="' + document.getElementById('contactform_id').value + '"]';

        tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
        tb_remove();
    });
</script>
