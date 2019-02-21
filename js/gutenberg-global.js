const el = wp.element.createElement;
const { registerBlockType } = wp.blocks;
const {
    SelectControl,
    TextControl,
    ToggleControl,
} = wp.components;

const {
    registerStore,
    withSelect,
} = wp.data;

const { __ } = wp.i18n;

const recrasHelper = {
    serverSideRender: () => null,

    elementInfo: (text) => {
        return el(
            'p',
            {
                class: 'recrasInfoText',
            },
            text
        );
    },
    elementText: (text) => {
        return el(
            'div',
            null,
            text
        );
    },
    parseBoolean: (value) => {
        return value ? 1 : 0;
    },

    typeBoolean: (defVal) => ({
        default: (defVal !== undefined) ? defVal : true,
        type: 'boolean',
    }),
    typeString: (defVal) => ({
        default: (defVal !== undefined) ? defVal : '',
        type: 'string',
    }),
};

const mapPagesPosts = function(pagePost, prefix) {
    // SelectControl does not support optgroups :(
    // https://github.com/WordPress/gutenberg/issues/8426
    return {
        label: prefix + pagePost.title.rendered,
        value: pagePost.link,
    };
};

const actions = {
    setPagesPosts(pagesPosts) {
        return {
            type: 'SET_PAGES_POSTS',
            pagesPosts,
        }
    },
    fetchPagesPosts(path) {
        return {
            type: 'FETCH_PAGES_POSTS',
            path,
        }
    },
};
const store = registerStore('recras/pages-posts', {
    reducer(state = { pagesPosts: {} }, action) {
        switch (action.type) {
            case 'SET_PAGES_POSTS':
                return {
                    ...state,
                    pagesPosts: action.pagesPosts,
                };
        }

        return state;
    },
    actions,
    selectors: {
        fetchPagesPosts(state) {
            const { pagesPosts } = state;
            return pagesPosts;
        }
    },
    controls: {
        FETCH_PAGES_POSTS(action) {
            return wp.apiFetch({
                path: action.path,
            });
        }
    },
    resolvers: {
        // * makes it a generator function
        * fetchPagesPosts(state) {
            let pagesPosts = [{
                label: '',
                value: '',
            }];

            let pages = yield actions.fetchPagesPosts('wp/v2/pages');
            pages = pages.map(p => {
                return mapPagesPosts(p, __('Page: '));
            });
            pagesPosts = pagesPosts.concat(pages);

            let posts = yield actions.fetchPagesPosts('wp/v2/posts');
            posts = posts.map(p => {
                return mapPagesPosts(p, __('Post: '));
            });
            pagesPosts = pagesPosts.concat(posts);

            return actions.setPagesPosts(pagesPosts);
        }
    }
});
