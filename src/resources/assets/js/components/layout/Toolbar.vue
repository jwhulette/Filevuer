<template>
  <div class="row">
    <div class="col-8 toolbar">
      <div 
        class="btn-group" 
        role="group">
        <button 
          type="button" 
          class="btn btn-primary btn-sm" 
          @click.prevent="toggleModal('fvUpload')">
          <font-awesome-icon icon="upload" /> &nbsp;Upload
        </button>
      </div>
      <div 
        class="btn-group" 
        role="group">
        <button 
          :class="{disabled: ! hasSelectedFiles}" 
          type="button" 
          class="btn btn-primary  btn-sm" 
          @click.prevent="download">
          <font-awesome-icon icon="download" /> &nbsp;Download
        </button>
      </div>

      <div 
        class="btn-group" 
        role="group">
        <div class="dropdown">
          <button 
            id="dropdownMenuButton" 
            class="btn btn-success  btn-sm dropdown-toggle" 
            type="button" 
            data-toggle="dropdown" 
            aria-haspopup="true" 
            aria-expanded="false">
            <font-awesome-icon icon="plus-square" /> &nbsp;Create
          </button>
          <div 
            class="dropdown-menu" 
            aria-labelledby="dropdownMenuButton">
            <a 
              class="dropdown-item" 
              href="#" 
              @click.prevent="toggleModal('fvCreate','file')"><font-awesome-icon icon="file-alt" /> &nbsp;Create File</a>
            <a 
              class="dropdown-item" 
              href="#" 
              @click.prevent="toggleModal('fvCreate','directory')"><font-awesome-icon icon="folder" /> &nbsp;Create Folder</a>
          </div>
        </div>
      </div>

      <div 
        class="btn-group" 
        role="group">
        <button 
          type="button" 
          class="btn btn-info  btn-sm" 
          @click.prevent="refresh">
          <font-awesome-icon icon="sync-alt" /> &nbsp;Refresh
        </button>
      </div>
    </div>
    <div class="toolbar col text-right">
      <div 
        class="btn-group" 
        role="group">
        <button 
          :class="{disabled: ! hasSelectedFiles}" 
          type="button" 
          class="btn btn-danger btn-sm" 
          @click.prevent="toggleModal('fvDelete')">
          <font-awesome-icon icon="trash-alt" /> &nbsp;Delete
        </button>
      </div>
    </div>
  </div>
</template>

<script>
    import * as types from '../../store/types';
    import {
        mapActions,
        mapState
    } from 'vuex';
    export default {
        computed: {
            ...mapState({
                hasSelectedFiles: state => state.files.filter(file => file.checked).length > 0
            })
        },      
        methods: {
            ...mapActions({
                download: types.DOWNLOAD_SELECTED,
                refresh: types.REFRESH,
            }),
            toggleModal(identifier, newFileFolder) {
                this.$store.commit(types.SET_FILE_FOLDER, newFileFolder);
                this.$root.$emit('bv::show::modal', identifier)
            }
        },
    }
</script>
