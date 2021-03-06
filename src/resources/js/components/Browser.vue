<template>
    <div>
        <div class="row justify-content-md-center">
            <div class="browser rounded col col-12 col-xl-8 col-lg-10 bg-white">
                <breadcrumbs />
                <toolbar />
                <file-list />
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import Breadcrumbs from './layout/Breadcrumbs.vue';
import Toolbar from './layout/Toolbar.vue';
import FileList from './filelist/FileList.vue';
import * as types from '../store/types';
import store from '../store';

export default {
    components: {
        Breadcrumbs,
        Toolbar,
        FileList,
    },
    props: {
        selected: {
            default: '',
            type: String,
        },
    },
    computed: {
        ...mapState({
            isLoading: (state) => state.isLoading,
        }),
    },
    created() {
        store.dispatch(types.FETCH_FILES, '/');
    },
    mounted() {
        if (this.selected !== '') {
            store.commit(types.SET_CONNCTION_NAME, this.selected);
        }
    },
};
</script>
