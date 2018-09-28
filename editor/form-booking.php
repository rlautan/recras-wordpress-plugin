<style>
    #TB_window #adminmenumain, #TB_window #wpfooter { display: none; }
    #TB_window #wpcontent { margin-left: 0; }
</style>
<?php
    $subdomain = get_option('recras_subdomain');

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
    <dt><label for="use_new_library"><?php _e('Use new method?', \Recras\Plugin::TEXT_DOMAIN); ?></label>
        <dd><input type="checkbox" id="use_new_library" checked>
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
    <dt><label for="auto_resize"><?php _e('Automatic resize?', \Recras\Plugin::TEXT_DOMAIN); ?></label>
        <dd><input type="checkbox" id="auto_resize" disabled>

</dl>
<button class="button button-primary" id="booking_submit"><?php _e('Insert shortcode', \Recras\Plugin::TEXT_DOMAIN); ?></button>

<script>
    document.getElementById('use_new_library').addEventListener('change', function(){
        document.getElementById('auto_resize').disabled = document.getElementById('use_new_library').checked;
        document.getElementById('redirect_page').disabled = !document.getElementById('use_new_library').checked;
    });

    document.getElementById('booking_submit').addEventListener('click', function(){
        var arrangementID = document.getElementById('arrangement_id').value;
        var shortcode = '[<?= \Recras\Plugin::SHORTCODE_ONLINE_BOOKING; ?>';
        if (arrangementID !== '0') {
            shortcode += ' id="' + arrangementID + '"';
        }

        if (document.getElementById('use_new_library').checked) {
            shortcode += ' use_new_library=1';
            if (document.getElementById('redirect_page').value !== '') {
                shortcode += ' redirect="' + document.getElementById('redirect_page').value + '"';
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
