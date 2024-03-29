import { createStore } from 'vuex'

const store = createStore({
    state() {
        return {
            ufObject: null,
        }
    },
    getters: {
        getUfObject: (state) => state.ufObject,
    },
    mutations: {
        setUfObject(state, ufObject) {
            state.ufObject = ufObject;
        },
    },
});

export default store