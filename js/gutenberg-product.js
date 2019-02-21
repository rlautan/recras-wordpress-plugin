registerBlockType('recras/product', {
    title: __('Product'),
    icon: 'cart',
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
            placeholder: __('ID of the product'),
            label: __('ID of the product'),
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
                    value: 'description_long',
                    label: __('Description (long)'),
                },
                {
                    value: 'description',
                    label: __('Description (short)'),
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
                    value: 'image_url',
                    label: __('Image URL'),
                },
                {
                    value: 'minimum_amount',
                    label: __('Minimum amount'),
                },
                {
                    value: 'price_incl_vat',
                    label: __('Price (incl. VAT)'),
                },
                {
                    value: 'title',
                    label: __('Title'),
                },
            ],
            label: __('Property to show'),
        };

        retval.push(recrasHelper.elementText('Recras - ' + __('Product')));

        retval.push(el(TextControl, optionsIDControl));
        retval.push(el(SelectControl, optionsShowWhatControl));
        return retval;
    },

    save: recrasHelper.serverSideRender,
});
