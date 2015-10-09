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
                cmd : 'arrangement',
                image : url + '/../assets/arrangement.svg'
            });

            ed.addButton('recras-contact', {
                title : 'Contact Form',
                cmd : 'recras-contact',
                image : url + '/../assets/contact.svg'
            });
        },

        getInfo : function() {
            return {
                longname : 'Recras arrangement shortcode',
                author : 'Recras',
                authorurl : 'https://www.recras.nl/',
                infourl : 'https://www.recras.nl/',
                version : "0.10.0"
            };
        }
    });

    tinymce.PluginManager.add('recras', tinymce.plugins.recras);
})();
