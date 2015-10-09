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
                cmd : 'contact',
                image : url + '/../assets/contact.svg'
            });

            ed.addCommand('arrangement', function() {
                var number = prompt(ed.getLang('recras.get_arrangement_id'));
                if (number) {
                    number = parseInt(number, 10);
                    if (number > 0) {
                        var shortcode = '[arrangement id="' + number + '" show="title"]';
                        ed.execCommand('mceInsertContent', 0, shortcode);
                    } else {
                        alert(ed.getLang('recras.id_positive'));
                    }
                }
            });

            ed.addCommand('contact', function() {
                var number = prompt(ed.getLang('recras.get_contact_id'));
                if (number) {
                    number = parseInt(number, 10);
                    if (number > 0) {
                        var shortcode = '[recras-contact id="' + number + '"]';
                        ed.execCommand('mceInsertContent', 0, shortcode);
                    } else {
                        alert(ed.getLang('recras.id_positive'));
                    }
                }
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
