registerBlockType('recras/package', {
    title: __('Package', TEXT_DOMAIN),
    icon: 'clipboard',
    category: 'recras',

    attributes: {
        id: recrasHelper.typeString(),
        show: recrasHelper.typeString('title'),
    },

    edit: withSelect((select) => {
        return {
            packages: select('recras/store').fetchPackages(),
        }
    })(props => {
        const {
            id,
            show,
        } = props.attributes;
        const {
            packages,
        } = props;

        let retval = [];
        const optionsIDControl = {
            value: id,
            onChange: function(newVal) {
                recrasHelper.lockSave('packageID', !newVal);
                props.setAttributes({
                    id: newVal,
                });
            },
            options: packages,
            label: __('Package', TEXT_DOMAIN),
        };
        const optionsShowWhatControl = {
            value: show,
            onChange: function(newVal) {
                props.setAttributes({
                    show: newVal
                });
            },
            options: [
                {
                    value: 'description',
                    label: __('Description', TEXT_DOMAIN),
                },
                {
                    value: 'duration',
                    label: __('Duration', TEXT_DOMAIN),
                },
                {
                    value: 'image_tag',
                    label: __('Image tag', TEXT_DOMAIN),
                },
                {
                    value: 'persons',
                    label: __('Minimum number of persons', TEXT_DOMAIN),
                },
                {
                    value: 'price_pp_excl_vat',
                    label: __('Price p.p. excl. VAT', TEXT_DOMAIN),
                },
                {
                    value: 'price_pp_incl_vat',
                    label: __('Price p.p. incl. VAT', TEXT_DOMAIN),
                },
                {
                    value: 'programme',
                    label: __('Programme', TEXT_DOMAIN),
                },
                {
                    value: 'location',
                    label: __('Starting location', TEXT_DOMAIN),
                },
                {
                    value: 'title',
                    label: __('Title', TEXT_DOMAIN),
                },
                {
                    value: 'price_total_excl_vat',
                    label: __('Total price excl. VAT', TEXT_DOMAIN),
                },
                {
                    value: 'price_total_incl_vat',
                    label: __('Total price incl. VAT', TEXT_DOMAIN),
                },
                {
                    value: 'image_url',
                    label: __('Relative image URL', TEXT_DOMAIN),
                },
            ],
            label: __('Property to show', TEXT_DOMAIN),
        };

        retval.push(recrasHelper.elementText('Recras - ' + __('Package', TEXT_DOMAIN)));

        retval.push(el(SelectControl, optionsIDControl));
        retval.push(el(SelectControl, optionsShowWhatControl));
        return retval;
    }),

    save: recrasHelper.serverSideRender,
});
