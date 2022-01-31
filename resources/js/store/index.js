import { createStore } from 'vuex';
import theme from './theme.js';
import auth from './auth.js';

const store = createStore({
    modules: {
        theme,
        auth,
    }
});

export default store;