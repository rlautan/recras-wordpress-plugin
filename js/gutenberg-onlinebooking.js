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

    edit: withSelect((select) => {
        return {
            packages: select('recras/store').fetchPackages(),
            pagesPosts: select('recras/store').fetchPagesPosts(),
        }
    })(props => {
        const {
            id,
            use_new_library,
            redirect,
            show_times,
            autoresize,
        } = props.attributes;
        const {
            packages,
            pagesPosts,
        } = props;

        let retval = [];
        const optionsPackageControl = {
            selected: id,
            onChange: function(newVal) {
                props.setAttributes({
                    id: newVal,
                });
            },
            options: packages,
            placeholder: __('Pre-filled package'),
            label: __('Pre-filled package (optional)'),
        };
        const optionsNewLibraryControl = {
            checked: use_new_library,
            onChange: function(newVal) {
                props.setAttributes({
                    use_new_library: recrasHelper.parseBoolean(newVal),
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
                        show_times: recrasHelper.parseBoolean(newVal),
                    });
                },
                label: __('Preview times in programme'),
            };
            optionsRedirectControl = {
                selected: redirect,
                onChange: function(newVal) {
                    props.setAttributes({
                        redirect: newVal
                    });
                },
                options: pagesPosts,
                placeholder: __('i.e. https://www.recras.com/thanks/'),
                label: __('URL to redirect to (optional, leave empty to not redirect)'),
            };
        } else {
            optionsAutoresizeControl = {
                checked: autoresize,
                onChange: function(newVal) {
                    props.setAttributes({
                        autoresize: recrasHelper.parseBoolean(newVal),
                    });
                },
                label: __('Auto resize iframe'),
            };
        }

        retval.push(el(SelectControl, optionsPackageControl));
        retval.push(el(ToggleControl, optionsNewLibraryControl));
        if (use_new_library) {
            retval.push(el(ToggleControl, optionsShowTimesControl));
            retval.push(el(SelectControl, optionsRedirectControl));
        } else {
            retval.push(el(ToggleControl, optionsAutoresizeControl));
        }
        return retval;
    }),

    save: function(props) {
    },
});
