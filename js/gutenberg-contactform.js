registerBlockType('recras/contactform', {
    title: __('Contact form'),
    icon: 'email',
    category: 'recras',

    attributes: {
        id: recrasHelper.typeString(),
        showtitle: recrasHelper.typeBoolean(true),
        showlabels: recrasHelper.typeBoolean(true),
        showplaceholders: recrasHelper.typeBoolean(true),
        arrangement: recrasHelper.typeString(),
        element: recrasHelper.typeString('dl'),
        single_choice_element: recrasHelper.typeString('select'),
        submittext: recrasHelper.typeString(__('Send')),
        redirect: recrasHelper.typeString(),
    },

    edit: withSelect((select) => {
        return {
            pagesPosts: select('recras/pages-posts').fetchPagesPosts(),
        }
    })(props => {
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
        const {
            pagesPosts
        } = props;

        let retval = [];
        const optionsIDControl = {
            value: id,
            onChange: function(newVal) {
                props.setAttributes({
                    id: newVal,
                });
            },
            placeholder: __('ID of the form'),
            label: __('ID of the form'),
            type: 'number',
            min: 1,
        };
        const optionsShowTitleControl = {
            checked: showtitle,
            onChange: function(newVal) {
                props.setAttributes({
                    showtitle: recrasHelper.parseBoolean(newVal),
                });
            },
            label: __('Show title?'),
        };
        const optionsShowLabelsControl = {
            checked: showlabels,
            onChange: function(newVal) {
                props.setAttributes({
                    showlabels: recrasHelper.parseBoolean(newVal),
                });
            },
            label: __('Show labels?'),
        };
        const optionsShowPlaceholdersControl = {
            checked: showplaceholders,
            onChange: function(newVal) {
                props.setAttributes({
                    showplaceholders: recrasHelper.parseBoolean(newVal),
                });
            },
            label: __('Show placeholders?'),
        };
        const optionsPackageControl = {
            value: arrangement,
            onChange: function(newVal) {
                props.setAttributes({
                    arrangement: newVal,
                });
            },
            placeholder: __('ID of the package (optional)'),
            label: __('ID of the package (optional)'),
            type: 'number',
            min: 0,
        };
        const optionsElementControl = {
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
        const optionsSingleChoiceControl = {
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
        const optionsSubmitTextControl = {
            value: submittext,
            onChange: function(newVal) {
                props.setAttributes({
                    submittext: newVal
                });
            },
            placeholder: __('Submit button text'),
            label: __('Submit button text'),
        };
        const optionsRedirectControl = {
            selected: redirect,
            onChange: function(newVal) {
                props.setAttributes({
                    redirect: newVal
                });
            },
            options: pagesPosts,
            placeholder: __('i.e. https://www.recras.com/thanks/'),
            label: __('URL to redirect to (optional, leave empty to not redirect)'),
            type: 'url',
        };

        retval.push(recrasHelper.elementText('Recras - ' + __('Contact form')));

        retval.push(el(TextControl, optionsIDControl));
        retval.push(el(ToggleControl, optionsShowTitleControl));
        retval.push(el(ToggleControl, optionsShowLabelsControl));
        retval.push(el(ToggleControl, optionsShowPlaceholdersControl));
        retval.push(el(TextControl, optionsPackageControl));

        //retval.push(recrasHelper.elementInfo(__('Some packages may not be available for all contact forms. You can change this by editing your contact forms in Recras.')));
        //retval.push(recrasHelper.elementInfo(__('If you are still missing packages, make sure "May be presented on a website (via API)" is enabled on the tab "Extra settings" of the package.')));

        retval.push(el(SelectControl, optionsElementControl));
        retval.push(el(SelectControl, optionsSingleChoiceControl));
        retval.push(el(TextControl, optionsSubmitTextControl));
        retval.push(el(SelectControl, optionsRedirectControl));
        return retval;
    }),

    save: recrasHelper.serverSideRender,
});
