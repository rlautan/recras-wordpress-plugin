var recrasPlugin = function(editor, url) {
    editor.addButton('recras-arrangement', {
        title : 'Package',
        image : url + '/arrangement.svg',
        onclick : function(){
            tb_show('Package', 'admin.php?page=form-arrangement');
        }
    });

    editor.addButton('recras-booking', {
        title : 'Online booking',
        image : url + '/calendar.svg',
        onclick : function(){
            tb_show('Online booking', 'admin.php?page=form-booking');
        }
    });

    editor.addButton('recras-contact', {
        title : 'Contact Form',
        image : url + '/contact.svg',
        onclick : function(){
            tb_show('Contact', 'admin.php?page=form-contact');
        }
    });

    editor.addButton('recras-product', {
        title : 'Product',
        image : url + '/product.svg',
        onclick : function(){
            tb_show('Product', 'admin.php?page=form-product');
        }
    });
};

tinymce.PluginManager.add('recras', recrasPlugin);
