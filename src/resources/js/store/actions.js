/* eslint-disable no-restricted-globals */
import alertify from 'alertifyjs';
import * as api from '../api';
import * as types from './types';

alertify.set('notifier', 'position', 'top-right');

const withPwd = (state, path) => {
    return `${state.path.replace(/^\/|\/$/g, '')}/${path}`;
};
const basename = (path) => {
    return path.split('/').pop();
};

// TODO: Find a better way to do this
const basePath = window.Filevuer.routePrefix;

export default {
    /**
     * Check to see if there is a valid session,
     * if not reload the window to log the user out.
     */
    [types.POLL_CONNECTION]() {
        return api.poll().then((response) => {
            if (response.active === false) {
                window.location.reload();
            }
        });
    },

    [types.SET_CONNECTION]({ commit }, selectedConnection) {
        commit(types.SET_LOADING, true);
        return api.setConnection(selectedConnection).then((response) => {
            commit(types.SET_LOADING, false);
            if (response === false) {
                alertify.error('Server login failed!');
                return false;
            }
            return true;
        });
    },

    [types.FETCH_FILES]({ commit }, path) {
        commit(types.SET_LOADING, true);
        commit(types.SET_PATH, path);

        api.getFiles(path)
            .then((files) => {
                commit(types.SET_FILELIST, files);
                commit(types.SET_LOADING, false);
            })
            // eslint-disable-next-line no-unused-vars
            .catch((e) => {
                alertify.error('Failed to fetch files');
                commit(types.SET_LOADING, false);
            });
    },

    [types.DELETE_SELECTED]({ dispatch, commit, state }) {
        const files = state.selected;

        const cleanUp = () => {
            commit(types.SET_LOADING, false);
        };

        commit(types.SET_LOADING, true);

        api.deleteFiles(files)
            .then(() => {
                alertify.success('Files deleted');
                dispatch(types.REFRESH);
                cleanUp();
            })
            // eslint-disable-next-line no-unused-vars
            .catch((e) => {
                alertify.error('Failed to delete file(s)');
                cleanUp();
            });
    },

    [types.DOWNLOAD_SELECTED]({ commit, state }) {
        const files = state.selected;

        const cleanUp = () => {
            commit(types.SET_LOADING, false);
        };

        commit(types.SET_LOADING, false);

        commit(types.SET_LOADING, true);

        api.download(files)
            .then((hash) => {
                location.assign(`${basePath}/download/${hash}`);
                commit(types.SET_SELECTED, false);
                alertify.success('Download started successfully');
                cleanUp();
            })
            // eslint-disable-next-line no-unused-vars
            .catch((e) => {
                alertify.error('Failed to download files');
                cleanUp();
            });
    },
    [types.CREATE_NEW]({ dispatch, commit, state }, { type, path }) {
        commit(types.SET_LOADING, true);
        const newPath = withPwd(state, path);

        api.create(type, newPath)
            .then(() => {
                // const newtype = type === 'file' ? '' : 'New folder';
                alertify.success(`New folder created`);
                dispatch(types.REFRESH);
            })
            // eslint-disable-next-line no-unused-vars
            .catch((e) => {
                alertify.error(`Failed to create ${type}`);
            });
    },

    [types.UPLOAD]({ commit, state, dispatch }, { files, extract }) {
        commit(types.SET_LOADING, true);

        return api
            .upload(files, state.path, extract)
            .then(() => {
                alertify.success('Upload successful');
                dispatch(types.REFRESH);
            })
            .catch((response) => {
                if (response.status === 422) {
                    alertify.error('The uploaded file is too large');
                } else {
                    alertify.error('Failed to upload files');
                }
                dispatch(types.REFRESH);
            });
    },

    [types.SET_SELECTED_FILES]({ commit }, selected) {
        commit(types.SET_SELECTED, selected);
    },

    [types.CHANGE_DIRECTORY]({ dispatch }, path) {
        dispatch(types.FETCH_FILES, path);
    },

    [types.CHANGE_DIRECTORY_RELATIVE]({ state, dispatch }, path) {
        // eslint-disable-next-line no-multi-assign, no-param-reassign
        const directoryPath = (state.path += `${path}/`);
        dispatch(types.CHANGE_DIRECTORY, directoryPath);
    },

    [types.REFRESH]({ state, dispatch }) {
        dispatch(types.FETCH_FILES, state.path);
    },

    [types.LEVEL_UP]({ dispatch, state }) {
        const path = state.path.replace(/^\/|\/$/g, '').split('/');
        path.pop();

        dispatch(
            types.CHANGE_DIRECTORY,
            path.length > 0 ? `/${path.join('/')}/` : '/'
        );
    },

    [types.UPDATE_FILELIST]({ commit }, files) {
        commit(types.SET_LOADING, false);
        commit(types.SET_SELECTED, false);
        commit(types.SET_FILELIST, files);
    },
};
