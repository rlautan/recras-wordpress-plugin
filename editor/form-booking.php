<?php
$subdomain = get_option('recras_subdomain');
if (!$subdomain) {
    \Recras\Settings::errorNoRecrasName();
    return;
}

$model = new \Recras\Arrangement;
$arrangements = $model->getArrangements($subdomain, true);
?>
<dl>
    <dt><label for="arrangement_id"><?php _e('Package', \Recras\Plugin::TEXT_DOMAIN); ?></label>
        <dd><?php if (is_string($arrangements)) { ?>
            <input type="number" id="arrangement_id" min="0">
            <?= $arrangements; ?>
        <?php } elseif(is_array($arrangements)) { ?>
            <?php unset($arrangements[0]); ?>
            <select id="arrangement_id" required>
                <option value="0"><?php _e('No pre-filled package', \Recras\Plugin::TEXT_DOMAIN); ?>
                <?php foreach ($arrangements as $ID => $arrangement) { ?>
                <option value="<?= $ID; ?>"><?= $arrangement->arrangement; ?>
                <?php } ?>
            </select>
        <?php } ?>
    <dt><label><?php _e('Integration method', \Recras\Plugin::TEXT_DOMAIN); ?></label>
        <dd>
            <label>
                <input type="radio" id="use_new_library_yes" name="integration_method" value="jslibrary" checked>
                <?php _e('Seamless (recommended)', \Recras\Plugin::TEXT_DOMAIN); ?>
            </label>
            <br>
            <label>
                <input type="radio" id="use_new_library_no" name="integration_method" value="iframe">
                <?php _e('iframe (uses setting in your Recras)', \Recras\Plugin::TEXT_DOMAIN); ?>
            </label>
    <dt><label for="show_times"><?php _e('Preview times in programme', \Recras\Plugin::TEXT_DOMAIN); ?></label>
        <dd><input type="checkbox" id="show_times">
    <dt><label><?php _e('Pre-fill amounts (requires pre-filled package)', \Recras\Plugin::TEXT_DOMAIN); ?></label>
        <dd><strong><?php _e('Sorry, this is only available using the Gutenberg editor.', \Recras\Plugin::TEXT_DOMAIN); ?></strong>
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
    <dt><label for="auto_resize"><?php _e('Automatic resize?', \Recras\Plugin::TEXT_DOMAIN); ?></label>
        <dd><input type="checkbox" id="auto_resize" disabled>

</dl>
<button class="button button-primary" id="booking_submit"><?php _e('Insert shortcode', \Recras\Plugin::TEXT_DOMAIN); ?></button>

<script>
    [...document.querySelectorAll('[name="integration_method"]')].forEach(function(el) {
        el.addEventListener('change', function(){
            var useLibrary = document.getElementById('use_new_library_yes').checked;
            document.getElementById('auto_resize').disabled = useLibrary;
            document.getElementById('redirect_page').disabled = !useLibrary;
            document.getElementById('show_times').disabled = !useLibrary;
        });
    });

    document.getElementById('booking_submit').addEventListener('click', function(){
        var arrangementID = document.getElementById('arrangement_id').value;
        var shortcode = '[<?= \Recras\Plugin::SHORTCODE_ONLINE_BOOKING; ?>';
        if (arrangementID !== '0') {
            shortcode += ' id="' + arrangementID + '"';
        }

        if (document.getElementById('use_new_library_yes').checked) {
            shortcode += ' use_new_library=1';
            if (document.getElementById('redirect_page').value !== '') {
                shortcode += ' redirect="' + document.getElementById('redirect_page').value + '"';
            }
            if (document.getElementById('show_times').checked) {
                shortcode += ' show_times=1';
            }
        } else {
            if (!document.getElementById('auto_resize').checked) {
                shortcode += ' autoresize=0';
            }
        }
        shortcode += ']';

        tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
        tb_remove();
    });
</script>
