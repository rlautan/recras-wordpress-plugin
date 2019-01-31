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
    title: __('Availability calendar'),
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
            label: __('ID of the package'),
            type: 'number',
            min: 1,
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
            label: __('Auto resize iframe'),
        };

        retval.push(el(
            'div',
            null,
            __('Recras - Availability calendar')
        ));
        /*if (id) {
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
        ));*/
        retval.push(el(TextControl, optionsIDControl));
        retval.push(el(CheckboxControl, optionsAutoresizeControl));
        return retval;
    },

    save: function() {
        return null; // Server-side render
    },
});

registerBlockType('recras/contactform', {
    title: __('Contact form'),
    icon: 'email',
    category: 'recras',

    attributes: {
        id: {
            type: 'string',
        },
        showtitle: {
            type: 'boolean',
            default: true,
        },
        showlabels: {
            type: 'boolean',
            default: true,
        },
        showplaceholders: {
            type: 'boolean',
            default: true,
        },
        arrangement: {
            type: 'string',
        },
        element: {
            type: 'string',
            default: 'dl',
        },
        single_choice_element: {
            type: 'string',
            default: 'select',
        },
        submittext: {
            type: 'string',
            default: __('Send'),
        },
        redirect: {
            type: 'string',
            default: '',
        },
    },

    edit: function(props) {
        const {
            id,
            showtitle,
            showlabels,
            showplaceholders,
            arrangement,
            element,
            single_choice_element,
            submittext,
            redirect,
        } = props.attributes;

        let retval = [];
        var optionsIDControl = {
            value: id,
            onChange: function(newVal) {
                props.setAttributes({
                    id: newVal
                });
            },
            placeholder: __('ID of the form'),
            label: __('ID of the form'),
            type: 'number',
            min: 1,
        };
        var optionsShowTitleControl = {
            checked: showtitle, // Existing 'id' value for the block.
            // When the text input value is changed, we need to
            // update the 'id' attribute to propagate the change.
            onChange: function(newVal) {
                props.setAttributes({
                    showtitle: newVal
                });
            },
            label: __('Show title?'),
        };
        var optionsShowLabelsControl = {
            checked: showlabels,
            onChange: function(newVal) {
                props.setAttributes({
                    showlabels: newVal
                });
            },
            label: __('Show labels?'),
        };
        var optionsShowPlaceholdersControl = {
            checked: showplaceholders,
            onChange: function(newVal) {
                props.setAttributes({
                    showplaceholders: newVal
                });
            },
            label: __('Show placeholders?'),
        };
        var optionsPackageControl = {
            value: arrangement,
            onChange: function(newVal) {
                props.setAttributes({
                    arrangement: newVal
                });
            },
            placeholder: __('ID of the package (optional)'),
            label: __('ID of the package (optional)'),
            type: 'number',
            min: 0,
        };
        var optionsElementControl = {
            value: element,
            onChange: function(newVal) {
                props.setAttributes({
                    element: newVal
                });
            },
            options: [
                {
                    value: 'dl',
                    label: __('Definition list'),
                },
                {
                    value: 'ol',
                    label: __('Ordered list'),
                },
                {
                    value: 'table',
                    label: __('Table'),
                },
            ],
            label: __('HTML element'),
        };
        var optionsSingleChoiceControl = {
            value: single_choice_element,
            onChange: function(newVal) {
                props.setAttributes({
                    single_choice_element: newVal
                });
            },
            options: [
                {
                    value: 'select',
                    label: __('Drop-down list (Select)'),
                },
                {
                    value: 'radio',
                    label: __('Radio buttons'),
                },
            ],
            label: __('Element for single choices'),
        };
        var optionsSubmitTextControl = {
            value: submittext,
            onChange: function(newVal) {
                props.setAttributes({
                    submittext: newVal
                });
            },
            placeholder: __('Submit button text'),
            label: __('Submit button text'),
        };
        var optionsRedirectControl = {
            value: redirect,
            onChange: function(newVal) {
                props.setAttributes({
                    redirect: newVal
                });
            },
            placeholder: __('i.e. https://www.recras.com/thanks/'),
            label: __('URL to redirect to (optional, leave empty to not redirect)'),
            type: 'url',
        };

        retval.push(el(
            'div',
            null,
            __('Recras - Contact form')
        ));

        retval.push(el(TextControl, optionsIDControl));
        retval.push(el(CheckboxControl, optionsShowTitleControl));
        retval.push(el(CheckboxControl, optionsShowLabelsControl));
        retval.push(el(CheckboxControl, optionsShowPlaceholdersControl));
        retval.push(el(TextControl, optionsPackageControl));
        retval.push(el(
            'p',
            {
                class: 'recrasInfoText',
            },
            __('Some packages may not be available for all contact forms. You can change this by editing your contact forms in Recras.')
        ));
        retval.push(el(
            'p',
            {
                class: 'recrasInfoText',
            },
            __('If you are still missing packages, make sure "May be presented on a website (via API)" is enabled on the tab "Extra settings" of the package.')
        ));
        retval.push(el(SelectControl, optionsElementControl));
        retval.push(el(SelectControl, optionsSingleChoiceControl));
        retval.push(el(TextControl, optionsSubmitTextControl));
        retval.push(el(TextControl, optionsRedirectControl));
        return retval;
    },

    save: function() {
        return null; // Server-side render
    },
});

//TODO: online booking
registerBlockType('recras/onlinebooking', {
    title: __('Online booking'),
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
    title: __('Package'),
    icon: 'clipboard',
    category: 'recras',

    attributes: {
        id: {
            type: 'string',
        },
        show: {
            type: 'string',
            default: 'title',
        }
    },

    edit: function(props) {
        const {
            id,
            show,
        } = props.attributes;

        let retval = [];
        var optionsIDControl = {
            value: id,
            onChange: function(newVal) {
                props.setAttributes({
                    id: newVal
                });
            },
            placeholder: __('ID of the package'),
            label: __('ID of the package'),
            type: 'number',
            min: 1,
        };
        var optionsShowWhatControl = {
            value: show,
            onChange: function(newVal) {
                props.setAttributes({
                    show: newVal
                });
            },
            options: [
                {
                    value: 'description',
                    label: __('Description'),
                },
                {
                    value: 'duration',
                    label: __('Duration'),
                },
                {
                    value: 'image_tag',
                    label: __('Image tag'),
                },
                {
                    value: 'persons',
                    label: __('Minimum number of persons'),
                },
                {
                    value: 'price_pp_excl_vat',
                    label: __('Price p.p. excl. VAT'),
                },
                {
                    value: 'price_pp_incl_vat',
                    label: __('Price p.p. incl. VAT'),
                },
                {
                    value: 'programme',
                    label: __('Programme'),
                },
                {
                    value: 'location',
                    label: __('Starting location'),
                },
                {
                    value: 'title',
                    label: __('Title'),
                },
                {
                    value: 'price_total_excl_vat',
                    label: __('Total price excl. VAT'),
                },
                {
                    value: 'price_total_incl_vat',
                    label: __('Total price incl. VAT'),
                },
                {
                    value: 'image_url',
                    label: __('Relative image URL'),
                },
            ],
            label: __('Show what'),
        };

        retval.push(el(
            'div',
            null,
            __('Recras - Package')
        ));

        retval.push(el(TextControl, optionsIDControl));
        retval.push(el(SelectControl, optionsShowWhatControl));
        return retval;
    },

    save: function() {
        return null; // Server-side render
    },
});

registerBlockType('recras/product', {
    title: __('Product'),
    icon: 'cart',
    category: 'recras',

    attributes: {
        id: {
            type: 'string',
        },
        show: {
            type: 'string',
            default: 'title',
        }
    },

    edit: function(props) {
        const {
            id,
            show,
        } = props.attributes;

        let retval = [];
        var optionsIDControl = {
            value: id,
            onChange: function(newVal) {
                props.setAttributes({
                    id: newVal
                });
            },
            placeholder: __('ID of the product'),
            label: __('ID of the product'),
            type: 'number',
            min: 1,
        };
        var optionsShowWhatControl = {
            value: show,
            onChange: function(newVal) {
                props.setAttributes({
                    show: newVal
                });
            },
            options: [
                {
                    value: 'description_long',
                    label: __('Description (long)'),
                },
                {
                    value: 'description',
                    label: __('Description (short)'),
                },
                {
                    value: 'duration',
                    label: __('Duration'),
                },
                {
                    value: 'image_tag',
                    label: __('Image tag'),
                },
                {
                    value: 'image_url',
                    label: __('Image URL'),
                },
                {
                    value: 'minimum_amount',
                    label: __('Minimum amount'),
                },
                {
                    value: 'price_incl_vat',
                    label: __('Price (incl. VAT)'),
                },
                {
                    value: 'title',
                    label: __('Title'),
                },
            ],
            label: __('Show what'),
        };

        retval.push(el(
            'div',
            null,
            __('Recras - Product')
        ));

        retval.push(el(TextControl, optionsIDControl));
        retval.push(el(SelectControl, optionsShowWhatControl));
        return retval;
    },

    save: function() {
        return null; // Server-side render
    },
});

//TODO: voucher sales
registerBlockType('recras/voucher', {
    title: __('Voucher'),
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
            __('Recras - Voucher sales module')
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
