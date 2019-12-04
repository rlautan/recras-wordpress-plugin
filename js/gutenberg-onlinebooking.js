registerBlockType('recras/onlinebooking', {
    title: __('Online booking', TEXT_DOMAIN),
    icon: 'admin-site',
    category: 'recras',
    example: {
        attributes: {
            autoresize: false,
            id: null,
            redirect: '',
            package_list: [],
            show_times: true,
            use_new_library: true,
            prefill_enabled: false,
            product_amounts: {},
        },
    },

    attributes: {
        autoresize: recrasHelper.typeBoolean(true),
        id: recrasHelper.typeString(),
        redirect: recrasHelper.typeString(),
        package_list: recrasHelper.typeString(), // stored as JSON string
        show_times: recrasHelper.typeBoolean(false),
        use_new_library: recrasHelper.typeBoolean(false),
        prefill_enabled: recrasHelper.typeBoolean(false),
        product_amounts: recrasHelper.typeString(), // stored as JSON string
    },

    edit: withSelect((select) => {
        return {
            packages: select('recras/store').fetchPackages(false, true),
            pagesPosts: select('recras/store').fetchPagesPosts(),
        }
    })(props => {
        if (!recrasOptions.subdomain) {
            return recrasHelper.elementNoRecrasName();
        }

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

        let packagesMapped = Object.values(packages);
        packagesMapped = packagesMapped.filter(p => p.mag_online);
        // Add empty value as first option, since package is not required
        packagesMapped.unshift({
            id: 0,
            arrangement: '',
        });
        packagesMapped = packagesMapped.map(mapPackage);

        let package_list;
        try {
            package_list = JSON.parse(props.attributes.package_list);
        } catch (e) {
            package_list = [];
        }

        let product_amounts;
        try {
            product_amounts = JSON.parse(props.attributes.product_amounts);
        } catch (e) {
            product_amounts = {};
        }
        let prefill_enabled = props.attributes.prefill_enabled || Object.keys(product_amounts).length > 0;

        let retval = [];
        const optionsNewLibraryControl = {
            selected: use_new_library ? 'jslibrary' : 'iframe',
            options: [
                {
                    label: __('Seamless (recommended)', TEXT_DOMAIN),
                    value: 'jslibrary',
                },
                {
                    label: __('iframe (uses setting in your Recras)', TEXT_DOMAIN),
                    value: 'iframe',
                },
            ],
            onChange: function(newVal) {
                const useJSLibrary = (newVal === 'jslibrary');
                props.setAttributes({
                    use_new_library: useJSLibrary,
                });
                if (useJSLibrary) {
                    props.setAttributes({
                        id: undefined,
                    });
                } else {
                    props.setAttributes({
                        package_list: undefined,
                    });
                }
            },
            label: __('Integration method', TEXT_DOMAIN),
        };
        let optionsShowTimesControl;
        let optionsPreFillControl;
        let preFillControls = [];
        let optionsRedirectControl;
        let optionsAutoresizeControl;
        let optionsPackageControl;

        if (use_new_library) {
            optionsPackageControl = {
                value: package_list,
                onChange: function(newVal) {
                    props.setAttributes({
                        package_list: JSON.stringify(newVal),
                    });
                },
                multiple: use_new_library,
                options: packagesMapped,
                label: __('Package(s) to show initially (optional)', TEXT_DOMAIN),
            };
            optionsShowTimesControl = {
                checked: show_times,
                onChange: function(newVal) {
                    props.setAttributes({
                        show_times: newVal,
                    });
                },
                label: __('Preview times in programme', TEXT_DOMAIN),
            };
            optionsPreFillControl = {
                checked: prefill_enabled,
                onChange: function(newVal) {
                    props.setAttributes({
                        prefill_enabled: newVal,
                    });
                },
                label: __('Pre-fill amounts (requires pre-filled package)', TEXT_DOMAIN),
            };
            optionsRedirectControl = {
                value: redirect,
                onChange: function(newVal) {
                    props.setAttributes({
                        redirect: newVal
                    });
                },
                options: pagesPosts,
                placeholder: __('i.e. https://www.recras.com/thanks/', TEXT_DOMAIN),
                label: __('Thank-you page (optional, leave empty to not redirect)', TEXT_DOMAIN),
            };

            if (prefill_enabled && id && packages[id]) {
                let linesNoBookingSize = packages[id].regels.filter(function(line) {
                    return line.onlineboeking_aantalbepalingsmethode !== 'boekingsgrootte';
                });
                let linesBookingSize = packages[id].regels.filter(function(line) {
                    return line.onlineboeking_aantalbepalingsmethode === 'boekingsgrootte';
                });
                if (linesBookingSize.length > 0) {
                    preFillControls.push({
                        value: product_amounts.bookingsize,
                        onChange: function(newVal) {
                            product_amounts.bookingsize = newVal;

                            props.setAttributes({
                                product_amounts: JSON.stringify(product_amounts)
                            });
                        },
                        label: packages[id].weergavenaam || packages[id].arrangement,
                        type: 'number',
                        min: 0,
                    });
                }
                linesNoBookingSize.forEach(line => {
                    let ctrl = {
                        value: product_amounts[line.id],
                        onChange: function(newVal) {
                            product_amounts[line.id] = newVal;

                            props.setAttributes({
                                product_amounts: JSON.stringify(product_amounts)
                            });
                        },
                        label: line.beschrijving_templated,
                        type: 'number',
                        min: 0,
                    };
                    preFillControls.push(ctrl);
                });
            }
        } else {
            optionsPackageControl = {
                value: id,
                onChange: function(newVal) {
                    props.setAttributes({
                        id: newVal,
                    });
                },
                options: packagesMapped,
                placeholder: __('Pre-filled package', TEXT_DOMAIN),
                label: __('Pre-filled package (optional)', TEXT_DOMAIN),
            };
            optionsAutoresizeControl = {
                checked: autoresize,
                onChange: function(newVal) {
                    props.setAttributes({
                        autoresize: newVal,
                    });
                },
                label: __('Auto resize iframe', TEXT_DOMAIN),
            };
        }

        retval.push(recrasHelper.elementText('Recras - ' + __('Online booking', TEXT_DOMAIN)));
        retval.push(el(RadioControl, optionsNewLibraryControl));
        retval.push(recrasHelper.elementInfo(
            __('Seamless integration takes the styling of your website. You can apply a bit of extra styling via the Recras → Settings menu.', TEXT_DOMAIN) +
            ' ' +
            __('iframe integration uses the setting in your Recras. You can change this via the Settings → Online booking page in your Recras and apply extra styling via the Settings → Other settings page in your Recras..', TEXT_DOMAIN)
        ));
        retval.push(el(SelectControl, optionsPackageControl));
        if (use_new_library) {
            retval.push(recrasHelper.elementInfo(
                __('If you are not seeing certain packages, make sure in Recras "May be presented on a website (via API)" is enabled on the tab "Extra settings" of the package.', TEXT_DOMAIN) +
                ' ' +
                __('You can leave this empty to show a dropdown of all packages, or select a single package to pre-fill it and skip the package selection step.', TEXT_DOMAIN)
            ));
            if (window.navigator.platform.includes('Mac')) {
                retval.push(recrasHelper.elementInfo(__('Select multiple packages by holding Cmd while clicking', TEXT_DOMAIN)));
            } else {
                retval.push(recrasHelper.elementInfo(__('Select multiple packages by holding Ctrl while clicking', TEXT_DOMAIN)));
            }
            retval.push(el(ToggleControl, optionsShowTimesControl));
            retval.push(el(ToggleControl, optionsPreFillControl));
            if (preFillControls.length) {
                preFillControls.forEach(ctrl => {
                    retval.push(el(TextControl, ctrl));
                });
            }
            retval.push(el(SelectControl, optionsRedirectControl));
        } else {
            retval.push(recrasHelper.elementInfo(
                __('If you are not seeing certain packages, make sure in Recras "May be presented on a website (via API)" is enabled on the tab "Extra settings" of the package.', TEXT_DOMAIN)
            ));
            retval.push(el(ToggleControl, optionsAutoresizeControl));
        }
        return retval;
    }),

    save: function(props) {
    },
});
