/* eslint-disable no-param-reassign */
import * as types from './types';

export default {
    [types.SET_CONNCTION_NAME](state, connectionName) {
        state.connectionName = connectionName;
    },

    [types.SET_FILE_FOLDER](state, newFileFolder) {
        state.newFileFolder = newFileFolder;
    },

    [types.SET_LOADING](state, isLoading) {
        state.isLoading = isLoading;
    },

    [types.SET_FILELIST](state, files) {
        state.files = files;
    },

    [types.SET_OPEN_FILE](state, path) {
        state.openFile = path;
    },

    [types.SET_SELECTED](state, selected) {
        if (typeof selected.selectedRows !== "undefined") {
            state.selected = selected.selectedRows;
        }
    },

    [types.SET_PATH](state, path) {
        state.path = path;
    },
};
