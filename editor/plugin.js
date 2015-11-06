(function() {
    tinymce.create('tinymce.plugins.recras', {
        /**
         * Initializes the plugin, this will be executed after the plugin has been created.
         * This call is done before the editor instance has finished it's initialization so use the onInit event
         * of the editor instance to intercept that event.
         */
        init : function(ed, url) {
            ed.addButton('recras-arrangement', {
                title : 'Arrangement',
                image : url + '/arrangement.svg',
                onclick : function(){
                    tb_show('Arrangement', 'admin.php?page=form-arrangement');
                }
            });

            ed.addButton('recras-contact', {
                title : 'Contact Form',
                image : url + '/contact.svg',
                onclick : function(){
                    tb_show('Contact', 'admin.php?page=form-contact');
                }
            });

            ed.addButton('recras-booking', {
                title : 'Online booking',
                image : url + '/calendar.svg',
                onclick : function(){
                    tb_show('Online booking', 'admin.php?page=form-booking');
                }
            });
        },

        getInfo : function() {
            return {
                longname : 'Recras arrangement shortcode',
                author : 'Recras',
                authorurl : 'https://www.recras.nl/',
                infourl : 'https://www.recras.nl/',
                version : "1.0.0"
            };
        }
    });

    tinymce.PluginManager.add('recras', tinymce.plugins.recras);
})();
