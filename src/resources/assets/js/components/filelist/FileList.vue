<template>
  <div class="row">

    <table 
      id="file-browser" 
      class="table table-sm table-hover">
      <thead>
        <tr>
          <th 
            scope="col" 
            class="text-center th-1">
            <input 
              :value="allSelected" 
              type="checkbox" 
              @change.prevent="toggleAll(!allSelected)">
          </th>
          <th 
            scope="col" 
            class="th-2" />
          <th 
            scope="col" 
            class="th-3">Filename</th>
          <th 
            scope="col" 
            class="th-4 text-right">Visibility</th>
          <th 
            scope="col" 
            class="th-5 text-right">Filesize</th>
        </tr>
      </thead>

      <tbody>
        <tr v-if="! isRootLevel">
          <td 
            colspan="5" 
            class="">
            &nbsp;&nbsp;&nbsp;
            <button 
              type="button" 
              class="btn btn-sm btn-secondary" 
              @click.prevent="levelUp">
              ... Up
              <font-awesome-icon icon="level-up-alt" />
            </button>
          </td>
        </tr>
        <tr 
          is="file-list-entry" 
          v-for="(item, index) in listing" 
          :item="item" 
          :key="index" />
      </tbody>
    </table>

  </div>
</template>

<script>
    import FileListEntry from './FileListEntry.vue';
    import * as types from '../../store/types';
    import {
        mapActions,
        mapState
    } from 'vuex';

    export default {
        components: {
            FileListEntry
        },
        computed: {
            ...mapState({
                allSelected: state => state.allSelected,
                listing: state => state.files,
                path: state => state.path
            }),
            isRootLevel () {
                return this.path === '/';
            }
        },
        methods: {
            ...mapActions({
                levelUp: types.LEVEL_UP,
                toggleAll: types.TOGGLE_ALL
            }),
        },


    }
</script>