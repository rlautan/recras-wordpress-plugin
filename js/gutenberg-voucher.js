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
            voucherTemplates: select('recras/store').fetchVoucherTemplates(),
        }
    })(props => {
        const {
            id,
            redirect,
        } = props.attributes;
        const {
            pagesPosts,
            voucherTemplates,
        } = props;

        if (pagesPosts === undefined || !pagesPosts.length) {
            return [
                recrasHelper.elementText(__('Loading data...'))
            ];
        }

        let retval = [];
        const optionsIDControl = {
            selected: id,
            onChange: function(newVal) {
                props.setAttributes({
                    id: newVal,
                });
            },
            options: voucherTemplates,
            label: __('Voucher template'),
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

        retval.push(el(SelectControl, optionsIDControl));
        retval.push(el(SelectControl, optionsRedirectControl));

        return retval;
    }),

    save: recrasHelper.serverSideRender,
});
