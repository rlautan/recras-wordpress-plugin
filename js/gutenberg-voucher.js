registerBlockType('recras/voucher', {
    title: __('Voucher'),
    icon: 'money',
    category: 'recras',

    attributes: {
        id: recrasHelper.typeString(),
        redirect: recrasHelper.typeString(),
    },

    edit: withSelect((select) => {
        return {
            pagesPosts: select('recras/store').fetchPagesPosts(),
        }
    })(props => {
        const {
            id,
            redirect,
        } = props.attributes;
        const {
            pagesPosts
        } = props;

        if (pagesPosts === undefined || !pagesPosts.length) {
            return [
                recrasHelper.elementText(__('Loading data...'))
            ];
        }

        let retval = [];
        const optionsIDControl = {
            value: id,
            onChange: function(newVal) {
                props.setAttributes({
                    id: newVal,
                });
            },
            placeholder: __('ID of the voucher template'),
            label: __('ID of the voucher template'),
            type: 'number',
            min: 1,
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
        };

        retval.push(el(TextControl, optionsIDControl));
        retval.push(el(SelectControl, optionsRedirectControl));

        return retval;
    }),

    save: recrasHelper.serverSideRender,
});
