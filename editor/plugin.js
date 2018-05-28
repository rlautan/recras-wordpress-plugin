var recrasPlugin = function(editor, url) {
    editor.addButton('recras-arrangement', {
        title: recras_l10n.package,
        image: url + '/package.svg',
        onclick: function() {
            tb_show(recras_l10n.package, 'admin.php?page=form-arrangement');
        }
    });

    editor.addButton('recras-availability', {
        title: recras_l10n.package_availability,
        image: url + '/availability.svg',
        onclick: function() {
            tb_show(recras_l10n.package_availability, 'admin.php?page=form-package-availability');
        }
    });

    editor.addButton('recras-booking', {
        title: recras_l10n.online_booking,
        image: url + '/online-booking.svg',
        onclick: function() {
            tb_show(recras_l10n.online_booking, 'admin.php?page=form-booking');
        }
    });

    editor.addButton('recras-contact', {
        title: recras_l10n.contact_form,
        image: url + '/contact.svg',
        onclick: function() {
            tb_show(recras_l10n.contact_form, 'admin.php?page=form-contact');
        }
    });

    editor.addButton('recras-product', {
        title: recras_l10n.product,
        image: url + '/product.svg',
        onclick: function() {
            tb_show(recras_l10n.product, 'admin.php?page=form-product');
        }
    });

    editor.addButton('recras-vouchers', {
        title: recras_l10n.vouchers,
        image: url + '/vouchers.svg',
        onclick: function() {
            tinyMCE.activeEditor.execCommand('mceInsertContent', 0, '[recras-vouchers]');
        }
    });
};

tinymce.PluginManager.add('recras', recrasPlugin);
