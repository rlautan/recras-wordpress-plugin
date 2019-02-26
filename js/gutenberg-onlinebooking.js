registerBlockType('recras/onlinebooking', {
    title: __('Online booking', TEXT_DOMAIN),
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
            placeholder: __('Pre-filled package', TEXT_DOMAIN),
            label: __('Pre-filled package (optional)', TEXT_DOMAIN),
        };
        const optionsNewLibraryControl = {
            checked: use_new_library,
            onChange: function(newVal) {
                props.setAttributes({
                    use_new_library: recrasHelper.parseBoolean(newVal),
                });
            },
            label: __('Use new method?', TEXT_DOMAIN),
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
                label: __('Preview times in programme', TEXT_DOMAIN),
            };
            optionsRedirectControl = {
                selected: redirect,
                onChange: function(newVal) {
                    props.setAttributes({
                        redirect: newVal
                    });
                },
                options: pagesPosts,
                placeholder: __('i.e. https://www.recras.com/thanks/', TEXT_DOMAIN),
                label: __('Redirect after submission (optional, leave empty to not redirect)', TEXT_DOMAIN),
            };
        } else {
            optionsAutoresizeControl = {
                checked: autoresize,
                onChange: function(newVal) {
                    props.setAttributes({
                        autoresize: recrasHelper.parseBoolean(newVal),
                    });
                },
                label: __('Auto resize iframe', TEXT_DOMAIN),
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
