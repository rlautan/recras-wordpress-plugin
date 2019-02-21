registerBlockType('recras/availability', {
    title: __('Availability calendar'),
    icon: 'calendar-alt',
    category: 'recras',

    attributes: {
        id: recrasHelper.typeString(),
        autoresize: recrasHelper.typeBoolean(true),
    },

    edit: function(props) {
        const {
            id,
            autoresize,
        } = props.attributes;

        let retval = [];
        const optionsIDControl = {
            value: id, // Existing 'id' value for the block.
            // When the text input value is changed, we need to
            // update the 'id' attribute to propagate the change.
            onChange: function(newVal) {
                props.setAttributes({
                    id: newVal,
                });
            },
            placeholder: __('ID of the package'),
            label: __('ID of the package'),
            type: 'number',
            min: 1,
        };
        const optionsAutoresizeControl = {
            checked: autoresize,
            onChange: function(newVal) {
                props.setAttributes({
                    autoresize: recrasHelper.parseBoolean(newVal),
                });
            },
            label: __('Auto resize iframe'),
        };

        retval.push(recrasHelper.elementText(__('Recras - Availability calendar')));
        retval.push(el(TextControl, optionsIDControl));
        retval.push(el(ToggleControl, optionsAutoresizeControl));
        return retval;
    },

    save: recrasHelper.serverSideRender,
});
