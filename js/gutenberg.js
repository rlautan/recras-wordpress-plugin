const el = wp.element.createElement;
const registerBlockType = wp.blocks.registerBlockType;
const TextControl = wp.components.TextControl;
const __ = wp.i18n.__;
const sprintf = wp.i18n.sprintf;

registerBlockType('recras/gutenberg-availability', {
    title: 'Availability calendar',
    icon: 'calendar-alt',
    category: 'recras',

    attributes: {
        id: {
            type: 'number',
            //source: 'attribute',
        },
        autoresize: {
            type: 'boolean',
            default: true,
            //source: 'attribute',
        }
    },

    edit: function(props) {
        let id = props.attributes.id;
        let autoresize = props.attributes.autoresize;
        let retval = [];
        if (!id) {
            var controlOptions = {
                // Existing 'url' value for the block.
                value: id,
                // When the text input value is changed, we need to
                // update the 'url' attribute to propagate the change.
                onChange: function(newVal) {
                    props.setAttributes({
                        id: newVal
                    });
                },
                placeholder: __('Enter the ID'),
            };
            retval.push(
                // el() is a function to instantiate a new element
                el( TextControl, controlOptions )
            );
        } else {
            retval.push(el(
                'div',
                null,
                sprintf(__('Recras Availability calendar for package %s'), id)
            ));
        }
        return retval;
    },

    save: function(props) {
        let id = props.attributes.id || false;
        let autoresize = props.attributes.autoresize || true;
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
} );
