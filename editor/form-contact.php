<?php
    require_once('../../../../wp-load.php');
    //require_once('/var/www/html/wordpress/wp-load.php');
?>
<dl>
    <dt><label for="contactform_id"><?php _e('Contact form ID', \Recras\Plugin::TEXT_DOMAIN); ?></label>
        <dd><input type="number" id="contactform_id" min="0" required> <!--TODO: dropdown-->
    <dt><?php _e('Show title?', \Recras\Plugin::TEXT_DOMAIN); ?>
        <dd>
            <input type="radio" name="header" value="yes" id="title_yes" checked><label for="title_yes"><?php _e('Yes', \Recras\Plugin::TEXT_DOMAIN); ?></label><br>
            <input type="radio" name="header" value="no" id="title_no"><label for="title_no"><?php _e('No', \Recras\Plugin::TEXT_DOMAIN); ?></label>
    <dt><label for="arrangement_id"><?php _e('Arrangement ID', \Recras\Plugin::TEXT_DOMAIN); ?></label>
        <dd><input type="number" id="arrangement_id" min="0"> <!--TODO: dropdown-->
</dl>
<button class="button button-primary" id="contact_submit"><?php _e('Insert shortcode', \Recras\Plugin::TEXT_DOMAIN); ?></button>

<script>
    document.getElementById('contact_submit').addEventListener('click', function(){
        var shortcode = '[recras-contact id="' + document.getElementById('contactform_id').value + '"';
        if (document.getElementById('title_no').checked) {
            shortcode += ' showtitle="no"';
        }
        if (document.getElementById('arrangement_id').value) {
            shortcode += ' arrangement="' + document.getElementById('arrangement_id').value + '"';
        }
        shortcode += ']';

        tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
        tb_remove();
    });
</script>
