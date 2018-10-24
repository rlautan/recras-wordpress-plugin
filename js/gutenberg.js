const el = wp.element.createElement;
const registerBlockType = wp.blocks.registerBlockType;
const TextControl = wp.components.TextControl;
const CheckboxControl = wp.components.CheckboxControl;
const __ = wp.i18n.__;
const sprintf = wp.i18n.sprintf;

registerBlockType('recras/availability', {
    title: 'Availability calendar',
    icon: 'calendar-alt',
    category: 'recras',

    attributes: {
        id: {
            type: 'number',
        },
        autoresize: {
            type: 'boolean',
            default: true,
        }
    },

    edit: function(props) {
        let id = props.attributes.id;
        let autoresize = props.attributes.autoresize;
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
        if (id) {
            retval.push(el(
                'div',
                null,
                sprintf(__('Recras Availability calendar for package %s'), id)
            ));
        }
        retval.push(el(TextControl, optionsIDControl));
        retval.push(el(CheckboxControl, optionsAutoresizeControl));
        return retval;
    },

    save: function(props) {
        let id = props.attributes.id || false;
        let autoresize = props.attributes.autoresize;
        if (autoresize === undefined) {
            autoresize = true;
        }
        // If the attributes ID is missing, don't save any inline HTML.
        if (!id) {
            return null;
        }
        // Include a fallback link for non-JS contexts and for when the plugin is not activated.
        let html = '[recras-availability id=' + id;
        if (!autoresize) {
            html += ' autoresize=false';
        }
        html += ']';
        return html;
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
    },

    save: function(props) {
    },
});
