<template>

  <div class="container-fluid">
    <div 
      id="app" 
      :class="{'editor-visible' : editorVisible}">
      <div v-if="loggedInState == true">
        <loading-overlay :visible="isLoading" />
        <modals/>
        <browser :selected="selected" />
        <editor :visible="editorVisible" />
      </div>
      <div v-else>
        <connection 
          :connection-list="connections" 
          @setLogin="setLogIn" />
      </div>
    </div>
  </div>

</template>

<script>
    import Connection from "./components/Connection.vue";
    import Editor from "./components/Editor.vue";
    import Browser from "./components/Browser.vue";
    import Modals from "./components/modals/Index.vue";
    import LoadingOverlay from "./components/layout/LoadingOverlay.vue";
    import {
        mapState
    } from "vuex";

    export default {
        components: {
            Connection,
            Editor,
            Browser,
            Modals,
            LoadingOverlay
        },        
        props: {
            loggedIn: {
                default: false,
                type: Boolean
            },
            connections: {
                default: null,
                type: Object
            },
            selected: {
                default: '',
                type: String
            }
        },
        data () {
            return {
                loggedInState: this.loggedIn,
            }
        },
        computed: {
            ...mapState({
                isLoading: state => state.isLoading,
                editorVisible: state => state.editorVisible,
            })
        },
        methods: {
            setLogIn: function (loggedInState) {
                this.loggedInState = loggedInState;
            },
        },
    };
</script>