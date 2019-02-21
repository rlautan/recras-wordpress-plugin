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
    lockSave: (lockName, bool) => {
        if (bool) {
            wp.data.dispatch('core/editor').lockPostSaving(lockName);
        } else {
            wp.data.dispatch('core/editor').unlockPostSaving(lockName);
        }
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

const mapSelect = function(label, value) {
    return {
        label: label,
        value: value,
    };
};
const mapPackage = function(pack) {
    return mapSelect(pack.arrangement, pack.id);
};
const mapPagesPosts = function(pagePost, prefix) {
    // SelectControl does not support optgroups :(
    // https://github.com/WordPress/gutenberg/issues/8426
    return mapSelect(prefix + pagePost.title.rendered, pagePost.link);
};

const recrasActions = {
    fetchAPI(path) {
        return {
            type: 'FETCH_API',
            path,
        }
    },

    setPackages(packages) {
        return {
            type: 'SET_PACKAGES',
            packages,
        }
    },

    setPagesPosts(pagesPosts) {
        return {
            type: 'SET_PAGES_POSTS',
            pagesPosts,
        }
    },
};
const recrasStore = registerStore('recras/store', {
    reducer(state = {
        packages: {},
        pagesPosts: {},
    }, action) {
        switch (action.type) {
            case 'SET_PACKAGES':
                return {
                    ...state,
                    packages: action.packages,
                };
            case 'SET_PAGES_POSTS':
                return {
                    ...state,
                    pagesPosts: action.pagesPosts,
                };
        }

        return state;
    },
    recrasActions,
    selectors: {
        fetchPackages(state) {
            const { packages } = state;
            return packages;
        },
        fetchPagesPosts(state) {
            const { pagesPosts } = state;
            return pagesPosts;
        },
    },
    controls: {
        FETCH_API(action) {
            return wp.apiFetch({
                path: action.path,
            });
        }
    },
    resolvers: {
        // * makes it a generator function
        * fetchPackages(state) {
            let packages = yield recrasActions.fetchAPI('recras/packages');
            packages = Object.values(packages).map(mapPackage);
            console.log(packages);

            return recrasActions.setPackages(packages);
        },
        * fetchPagesPosts(state) {
            let pagesPosts = [{
                label: '',
                value: '',
            }];

            let pages = yield recrasActions.fetchAPI('wp/v2/pages');
            pages = pages.map(p => {
                return mapPagesPosts(p, __('Page: '));
            });
            pagesPosts = pagesPosts.concat(pages);

            let posts = yield recrasActions.fetchAPI('wp/v2/posts');
            posts = posts.map(p => {
                return mapPagesPosts(p, __('Post: '));
            });
            pagesPosts = pagesPosts.concat(posts);

            return recrasActions.setPagesPosts(pagesPosts);
        },
    }
});
