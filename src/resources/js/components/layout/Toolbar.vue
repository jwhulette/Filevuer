<template>
    <div class="row">
        <div class="col-8 toolbar">
            <b-button-group size="sm">
                <b-button
                    size="sm"
                    variant="primary"
                    @click.prevent="toggleModal('fvUpload')"
                >
                    <font-awesome-icon icon="upload" /> &nbsp;Upload
                </b-button>

                <b-button
                    :class="{ disabled: !hasSelectedFiles }"
                    size="sm"
                    variant="primary"
                    @click.prevent="download"
                >
                    <font-awesome-icon icon="download" /> &nbsp;Download
                </b-button>

              <b-button 
                size="sm" 
                variant="success"
                @click.prevent="toggleModal('fvCreate', 'directory')">
                    <font-awesome-icon icon="folder" /> &nbsp;Create Folder
                </b-button>

                <b-button size="sm" variant="info" @click.prevent="refresh">
                    <font-awesome-icon icon="sync-alt" /> &nbsp;Refresh
                </b-button>
            </b-button-group>
        </div>

        <div class="toolbar col text-right">
            <b-button
                :class="{ disabled: !hasSelectedFiles }"
                type="button"
                class="btn btn-danger btn-sm"
                @click.prevent="toggleModal('fvDelete')"
            >
                <font-awesome-icon icon="trash-alt" /> &nbsp;Delete
            </b-button>
        </div>
    </div>
</template>

<script>

import { mapActions, mapState } from 'vuex';
import * as types from '../../store/types';

export default {
    computed: {
        ...mapState({
            hasSelectedFiles: (state) => state.selected.length > 0,
        }),
    },
    methods: {
        ...mapActions({
            download: types.DOWNLOAD_SELECTED,
            refresh: types.REFRESH,
        }),
        toggleModal(identifier, newFileFolder) {
            this.$store.commit(types.SET_FILE_FOLDER, newFileFolder);
            this.$root.$emit('bv::show::modal', identifier);
        },
    },
};
</script>
