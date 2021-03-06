import Vue from 'vue';
import Vuex from 'vuex';
import actions from './actions';
import mutations from './mutations';

const state = {
    routePrefix: 'filevuer',
    files: [],
    openFile: null,
    path: '/',
    isLoading: false,
    connectionName: '',
    newFileFolder: 'file',
    selected: false,
    visibleModals: {
        confirmDelete: false,
        create: false,
        upload: false,
    },
};

Vue.use(Vuex);

export default new Vuex.Store({
    state,
    mutations,
    actions,
});
