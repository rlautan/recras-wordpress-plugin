registerBlockType('recras/product', {
    title: __('Product', TEXT_DOMAIN),
    icon: 'cart',
    category: 'recras',

    attributes: {
        id: recrasHelper.typeString(),
        show: recrasHelper.typeString('title'),
    },

    edit: withSelect((select) => {
        return {
            products: select('recras/store').fetchProducts(),
        }
    })(props => {
        const {
            id,
            show,
        } = props.attributes;
            const {
                products,
            } = props;

        let retval = [];
        const optionsIDControl = {
            selected: id,
            onChange: function(newVal) {
                props.setAttributes({
                    id: newVal,
                });
            },
            options: products,
            label: __('Product', TEXT_DOMAIN),
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
                    label: __('Description (long)', TEXT_DOMAIN),
                },
                {
                    value: 'description',
                    label: __('Description (short)', TEXT_DOMAIN),
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
                    value: 'image_url',
                    label: __('Image URL', TEXT_DOMAIN),
                },
                {
                    value: 'minimum_amount',
                    label: __('Minimum amount', TEXT_DOMAIN),
                },
                {
                    value: 'price_incl_vat',
                    label: __('Price (incl. VAT)', TEXT_DOMAIN),
                },
                {
                    value: 'title',
                    label: __('Title', TEXT_DOMAIN),
                },
            ],
            label: __('Property to show', TEXT_DOMAIN),
        };

        retval.push(recrasHelper.elementText('Recras - ' + __('Product', TEXT_DOMAIN)));

        retval.push(el(SelectControl, optionsIDControl));
        retval.push(el(SelectControl, optionsShowWhatControl));
        return retval;
    }),

    save: recrasHelper.serverSideRender,
});
