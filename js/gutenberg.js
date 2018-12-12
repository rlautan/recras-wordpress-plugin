const el = wp.element.createElement;
const { registerBlockType } = wp.blocks;
const {
    TextControl,
    CheckboxControl,
    SelectControl
} = wp.components;

const {
    __,
    sprintf
} = wp.i18n;

registerBlockType('recras/availability', {
    title: 'Availability calendar',
    icon: 'calendar-alt',
    category: 'recras',

    attributes: {
        id: {
            type: 'string',
        },
        autoresize: {
            type: 'boolean',
            default: true,
        }
    },

    edit: function(props) {
        const {
            id,
            autoresize,
        } = props.attributes;

        let retval = [];
        var optionsIDControl = {
            value: id, // Existing 'id' value for the block.
            // When the text input value is changed, we need to
            // update the 'id' attribute to propagate the change.
            onChange: function(newVal) {
                props.setAttributes({
                    id: newVal
                });
            },
            placeholder: __('ID of the package'),
            label: 'ID of the package',
        };
        var optionsAutoresizeControl = {
            checked: autoresize, // Existing 'id' value for the block.
            // When the text input value is changed, we need to
            // update the 'id' attribute to propagate the change.
            onChange: function(newVal) {
                props.setAttributes({
                    autoresize: newVal
                });
            },
            label: 'Auto resize iframe',
        };

        retval.push(el(
            'div',
            null,
            __('Recras Availability calendar')
        ));
        if (id) {
            retval.push(el(
                'div',
                null,
                sprintf(__('Package: %s'), id)
            ));
        }
        retval.push(el(
            'div',
            null,
            sprintf(__('Auto resize?: %s'), autoresize ? __('yes') : __('no'))
        ));
        retval.push(el(TextControl, optionsIDControl));
        retval.push(el(CheckboxControl, optionsAutoresizeControl));
        return retval;
    },

    save: function() {
        return null; // Server-side render
    },
});

registerBlockType('recras/contactform', {
    title: 'Contact form',
    icon: 'email',
    category: 'recras',

    attributes: {
    },

    edit: function(props) {
    },

    save: function(props) {
    },
});

registerBlockType('recras/onlinebooking', {
    title: 'Online booking',
    icon: 'admin-site',
    category: 'recras',

    attributes: {
    },

    edit: function(props) {
    },

    save: function(props) {
    },
});

registerBlockType('recras/package', {
    title: 'Package',
    icon: 'clipboard',
    category: 'recras',

    attributes: {
    },

    edit: function(props) {
    },

    save: function(props) {
    },
});

registerBlockType('recras/product', {
    title: 'Product',
    icon: 'cart',
    category: 'recras',

    attributes: {
    },

    edit: function(props) {
    },

    save: function(props) {
    },
});

registerBlockType('recras/voucher', {
    title: 'Voucher',
    icon: 'money',
    category: 'recras',

    attributes: {
    },

    edit: function(props) {
        let template = props.attributes.template;
        let redirect = props.attributes.redirect;
        let pagesPosts = [];

        let retval = [];
        var optionsIDControl = {
            value: template, // Existing 'template' value for the block.
            // When the text input value is changed, we need to
            // update the 'template' attribute to propagate the change.
            onChange: function(newVal) {
                props.setAttributes({
                    template: newVal
                });
            },
            placeholder: __('ID of the template'),
            label: 'ID of the template',
        };

        var optionsRedirectControl = {
            selected: redirect, // Existing 'template' value for the block.
            // When the text input value is changed, we need to
            // update the 'template' attribute to propagate the change.
            onChange: function(newVal) {
                props.setAttributes({
                    redirect: newVal
                });
            },
            options: [],
            label: 'Redirect',
        };

        const addOptions = function(posts, prefix) {
            let options = [];
            posts.forEach(post => {
                options.push({
                    label: prefix + post.title.rendered, //SelectControl does not support optgroups :(
                    value: post.id,
                });
            });
            return options;
        };

        let pagesPromise = wp.apiFetch({
            path: 'wp/v2/pages',
        })
            .then(pages => addOptions(pages, __('Page: ')))
            .then(options => {
                optionsRedirectControl.options = optionsRedirectControl.options.concat(options);
            });

        let postsPromise = wp.apiFetch({
            path: 'wp/v2/posts',
        })
            .then(posts => addOptions(posts, __('Post: ')))
            .then(options => {
                optionsRedirectControl.options = optionsRedirectControl.options.concat(options);
            });

        retval.push(el(
            'div',
            null,
            __('Recras Voucher sales module')
        ));
        if (template) {
            retval.push(el(
                'div',
                null,
                sprintf(__('Template: %s'), template)
            ));
        }
        if (redirect) {
            retval.push(el(
                'div',
                null,
                sprintf(__('Redirecting to %s after submitting'), redirect)
            ));
        }
        retval.push(el(TextControl, optionsIDControl));

        Promise.all([pagesPromise, postsPromise]).then(() => {
            console.log('promises resolved', optionsRedirectControl.options);
            retval.push(el(SelectControl, optionsRedirectControl));
            /*setState({
                posts: optionsRedirectControl.options,
            });*/
        });
        return '';
    },

    save: function() {
        return null; // Server-side render
    },
});
