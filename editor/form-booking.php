<style>
    #TB_window #adminmenumain, #TB_window #wpfooter { display: none; }
    #TB_window #wpcontent { margin-left: 0; }
</style>
<?php
    /*$subdomain = get_option('recras_subdomain');

    $model = new \Recras\Arrangement;
    $arrangements = $model->getArrangements($subdomain);
    unset($arrangements[0]);*/
?>
<!--<dl>
    <dt><label for="arrangement_id"><?php /*_e('Arrangement', \Recras\Plugin::TEXT_DOMAIN); */?></label>
        <dd><?php /*if (is_string($arrangements)) { */?>
            <input type="number" id="arrangement_id" min="0" required>
            <?/*= $arrangements; */?>
        <?php /*} elseif(is_array($arrangements)) { */?>
            <select id="arrangement_id" required>
                <option value="0"><?php /*_e('No pre-filled arrangement', \Recras\Plugin::TEXT_DOMAIN); */?>
                <?php /*foreach ($arrangements as $ID => $arrangement) { */?>
                <option value="<?/*= $ID; */?>"><?/*= $arrangement->arrangement; */?>
                <?php /*} */?>
            </select>
        <?php /*} */?>
</dl>
<button class="button button-primary" id="booking_submit"><?php /*_e('Insert shortcode', \Recras\Plugin::TEXT_DOMAIN); */?></button>
-->
<script>
    //document.getElementById('booking_submit').addEventListener('click', function(){
        //var arrangementID = document.getElementById('arrangement_id').value;
        var arrangementID = '0';
        var shortcode  = '[recras-booking';
        if (arrangementID !== '0') {
            shortcode += ' id="' + arrangementID + '"';
        }
        shortcode += ']';

        tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
        tb_remove();
    //});
</script>
