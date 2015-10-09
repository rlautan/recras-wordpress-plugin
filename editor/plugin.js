(function() {
    tinymce.create('tinymce.plugins.recras', {
        /**
         * Initializes the plugin, this will be executed after the plugin has been created.
         * This call is done before the editor instance has finished it's initialization so use the onInit event
         * of the editor instance to intercept that event.
         */
        init : function(ed, url) {
            ed.addButton('arrangement', {
                title : 'Arrangement',
                image : url + '/../assets/arrangement.svg',
                onclick : function(){
                    tb_show('Foo', url + '/form-arrangement.php?height=300');
                }
            });

            ed.addButton('recras-contact', {
                title : 'Contact Form',
                image : url + '/../assets/contact.svg',
                onclick : function(){
                    tb_show('Foo', url + '/form-contact.php?height=300');
                }
            });
        },

        getInfo : function() {
            return {
                longname : 'Recras arrangement shortcode',
                author : 'Recras',
                authorurl : 'https://www.recras.nl/',
                infourl : 'https://www.recras.nl/',
                version : "0.11.0"
            };
        }
    });

    tinymce.PluginManager.add('recras', tinymce.plugins.recras);
})();
