registerBlockType('recras/package', {
    title: __('Package'),
    icon: 'clipboard',
    category: 'recras',

    attributes: {
        id: recrasHelper.typeString(),
        show: recrasHelper.typeString('title'),
    },

    edit: function(props) {
        const {
            id,
            show,
        } = props.attributes;

        let retval = [];
        const optionsIDControl = {
            value: id,
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
            label: __('Show what'),
        };

        retval.push(recrasHelper.elementText(__('Recras - Package')));

        retval.push(el(TextControl, optionsIDControl));
        retval.push(el(SelectControl, optionsShowWhatControl));
        return retval;
    },

    save: recrasHelper.serverSideRender,
});
