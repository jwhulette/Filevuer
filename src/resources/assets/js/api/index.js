import axios from 'axios';

const http = axios;
const basePath = '/filevuer';

const removeSlashes = (str) => str.replace(/^\/|\/$/g, '');

export function poll() {
    return http({
        url: `${basePath}/poll`,
        method: 'get',
    }).then((response) => {
        return response.data;
    });
}

export function setConnection(selected) {
    return http({
        url: basePath,
        method: 'post',
        data: {
            connection: selected,
        },
    }).then((response) => {
        return response.data;
    });
}

/**
 * Get a list of files
 * @param {string} path
 */
export function getFiles(path) {
    return http({
        url: `${basePath}/directory/?path=${removeSlashes(path)}`,
        method: 'GET',
    }).then((response) => {
        response.data.listing.map((item) => {
            // eslint-disable-next-line no-param-reassign
            item.checked = false;
            // eslint-disable-next-line no-underscore-dangle, no-param-reassign
            item._uid = +Date.now();

            return item;
        });

        return response.data.listing;
    });
}

/**
 * Get the contents of a file
 * @param {string} path
 */
export function getContents(path) {
    return http({
        url: `${basePath}/file/?path=${removeSlashes(path)}`,
        method: 'GET',
    }).then((response) => response.data);
}

/**
 * Put the contents of a file
 * @param {string} path
 * @param {string} contents
 */
export function putContents(path, contents) {
    const contentsPath = removeSlashes(path);
    return http({
        url: `${basePath}/file`,
        method: 'put',
        data: {
            contentsPath,
            contents,
        },
    }).then((response) => response.data);
}

/**
 * Delete files
 * @param {array} entries
 */
export function deleteFiles(entries) {
    const files = entries
        .filter((entry) => entry.type === 'file')
        .map((entry) => entry.path);
    const directories = entries
        .filter((entry) => entry.type === 'dir')
        .map((entry) => entry.path);

    return Promise.all([
        http({
            url: `${basePath}/file`,
            method: 'delete',
            data: {
                path: files,
            },
        }),
        http({
            url: `${basePath}/directory`,
            method: 'delete',
            data: {
                path: directories,
            },
        }),
    ]);
}

/**
 * Create a new file
 * @param {string} type
 * @param {string} path
 */
export function create(type, path) {
    return http({
        url: `${basePath}/${type}`,
        method: 'post',
        data: {
            path,
        },
    });
}

/**
 * Download a file
 * @param {string} path
 */
export function download(path) {
    return http({
        url: `${basePath}/download`,
        method: 'post',
        data: {
            path,
        },
    }).then((response) => response.data);
}

/**
 * Upload a new file
 * @param {array} files
 * @param {string} path
 * @param {boolean} extract
 */
export function upload(files, path, extract) {
    const data = new FormData();
    for (let i = 0; i < files.length; i + 1) {
        data.append('files[]', files[i]);
    }

    data.append('path', path);
    data.append('extract', extract ? 1 : 0);

    return http({
        url: `${basePath}/upload`,
        method: 'post',
        data,
    });
}
