registerBlockType('recras/onlinebooking', {
    title: __('Online booking'),
    icon: 'admin-site',
    category: 'recras',

    attributes: {
        autoresize: recrasHelper.typeBoolean(true),
        id: recrasHelper.typeString(),
        redirect: recrasHelper.typeString(),
        show_times: recrasHelper.typeBoolean(false),
        use_new_library: recrasHelper.typeBoolean(true),
    },

    edit: function(props) {
        const {
            id,
            use_new_library,
            redirect,
            show_times,
            autoresize,
        } = props.attributes;

        let retval = [];
        const optionsIDControl = {
            value: id,
            onChange: function(newVal) {
                props.setAttributes({
                    id: newVal,
                });
            },
            placeholder: __('ID of the pre-filled package'),
            label: __('ID of the pre-filled package (optional)'),
            type: 'number',
            min: 1,
        };
        const optionsNewLibraryControl = {
            checked: use_new_library,
            onChange: function(newVal) {
                props.setAttributes({
                    use_new_library: newVal
                });
            },
            label: __('Use new method?'),
        };
        let optionsShowTimesControl;
        let optionsRedirectControl;
        let optionsAutoresizeControl;
        if (use_new_library) {
            optionsShowTimesControl = {
                checked: show_times,
                onChange: function(newVal) {
                    props.setAttributes({
                        show_times: newVal
                    });
                },
                label: __('Preview times in programme'),
            };
            optionsRedirectControl = {
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
        } else {
            optionsAutoresizeControl = {
                checked: autoresize,
                onChange: function(newVal) {
                    props.setAttributes({
                        autoresize: newVal
                    });
                },
                label: __('Auto resize iframe'),
            };
        }

        retval.push(el(TextControl, optionsIDControl));
        retval.push(el(ToggleControl, optionsNewLibraryControl));
        if (use_new_library) {
            retval.push(el(ToggleControl, optionsShowTimesControl));
            retval.push(el(TextControl, optionsRedirectControl));
        } else {
            retval.push(el(ToggleControl, optionsAutoresizeControl));
        }
        return retval;
    },

    save: function(props) {
    },
});
