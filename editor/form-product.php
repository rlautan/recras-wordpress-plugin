<style>
    #TB_window #adminmenumain { display: none; }
    #TB_window #wpcontent { margin-left: 0; }
</style>
<?php
    $model = new \Recras\Products;
    $products = $model->getProducts(get_option('recras_subdomain'));
?>

<dl>
    <dt><label for="product_id"><?php _e('Product', \Recras\Plugin::TEXT_DOMAIN); ?></label>
    <dd><?php if (is_string($products)) { ?>
            <input type="number" id="product_id" min="0" required>
            <?= $products; ?>
        <?php } elseif(is_array($products)) { ?>
            <select id="product_id" required>
            <?php foreach ($products as $ID => $product) { ?>
                <option value="<?= $ID; ?>"><?= $product->weergavenaam; ?>
            <?php } ?>
            </select>
        <?php } ?>
    <dt><label for="show_what"><?php _e('Show what?', \Recras\Plugin::TEXT_DOMAIN); ?></label>
    <dd><select id="show_what" required>
            <option value="title"><?php _e('Title', \Recras\Plugin::TEXT_DOMAIN); ?>
            <option value="description"><?php _e('Description', \Recras\Plugin::TEXT_DOMAIN); ?>
            <option value="price_excl_vat"><?php _e('Price excl. VAT', \Recras\Plugin::TEXT_DOMAIN); ?>
            <option value="price_incl_vat"><?php _e('Price incl. VAT', \Recras\Plugin::TEXT_DOMAIN); ?>
        </select>
</dl>
<button class="button button-primary" id="product_submit"><?php _e('Insert shortcode', \Recras\Plugin::TEXT_DOMAIN); ?></button>

<script>
    document.getElementById('product_submit').addEventListener('click', function(){
        var shortcode = '[recras-product id="' + document.getElementById('product_id').value + '" show="' + document.getElementById('show_what').value + '"]';

        tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
        tb_remove();
    });
</script>
