<style>
    #TB_window #adminmenumain, #TB_window #wpfooter { display: none; }
    #TB_window #wpcontent { margin-left: 0; }
</style>
<?php
    $model = new \Recras\Vouchers;
?>

<dl>
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
<button class="button button-primary" id="voucher_submit"><?php _e('Insert shortcode', \Recras\Plugin::TEXT_DOMAIN); ?></button>

<script>
    document.getElementById('voucher_submit').addEventListener('click', function(){
        var shortcode = '[recras-vouchers';

        if (document.getElementById('redirect_page').value !== '') {
            shortcode += ' redirect="' + document.getElementById('redirect_page').value + '"';
        }

        shortcode += ']';

        tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
        tb_remove();
    });
</script>
