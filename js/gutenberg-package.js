registerBlockType('recras/package', {
    title: __('Package'),
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
            selected: id,
            onChange: function(newVal) {
                props.setAttributes({
                    id: newVal,
                });
            },
            options: packages,
            label: __('Package'),
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
            label: __('Property to show'),
        };

        retval.push(recrasHelper.elementText('Recras - ' + __('Package')));

        retval.push(el(SelectControl, optionsIDControl));
        retval.push(el(SelectControl, optionsShowWhatControl));
        return retval;
    }),

    save: recrasHelper.serverSideRender,
});
