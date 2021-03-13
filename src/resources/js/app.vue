<template>
    <div class="container-fluid">
        <div id="app" :class="{ 'editor-visible': editorVisible }">
            <div v-if="loggedInState == true">
                <loading-overlay :visible="isLoading" />
                <modals />
                <browser :selected="selected" />
            </div>
            <div v-else>
                <connection
                    :connection-list="connections"
                    @setLogin="setLogIn"
                />
            </div>
        </div>
    </div>
</template>

<script>
import { mapState } from 'vuex';
import Connection from './components/Connection.vue';
import Browser from './components/Browser.vue';
import Modals from './components/modals/Index.vue';
import LoadingOverlay from './components/layout/LoadingOverlay.vue';
import * as types from './store/types';
import store from './store';

export default {
    components: {
        Connection,
        Browser,
        Modals,
        LoadingOverlay,
    },
    props: {
        loggedIn: {
            default: false,
            type: Boolean,
        },
        connections: {
            default: null,
            type: Object,
        },
        selected: {
            default: '',
            type: String,
        }
    },
    data() {
        return {
            loggedInState: this.loggedIn,
            interval: null,
        };
    },
    computed: {
        ...mapState({
            isLoading: (state) => state.isLoading,
            editorVisible: (state) => state.editorVisible,
            connectionName: (state) => state.connectionName,
        }),
    },
    mounted() {
        /**
         * Poll to check if the user uas a valid session,
         * if not reload the page.
         */
        // eslint-disable-next-line func-names
        this.interval = setInterval(function () {
            store.dispatch(types.POLL_CONNECTION);
        }, 3600000);
    },
    beforeDestroy() {
        clearInterval(this.interval);
    },
    updated() {
        if (this.connectionName) {
            document.title = `Connection: ${this.connectionName}`;
        }
    },
    methods: {
        setLogIn(loggedInState) {
            this.loggedInState = loggedInState;
        },
    },
};
</script>
