<template>
  <tr>
    <td class="text-center">
      <input 
        :checked="item.checked" 
        :value="checked" 
        type="checkbox" 
        @change.prevent="toggleFile(item)">
    </td>
    <td class="text-center">
      <font-awesome-icon 
        :icon="icon" 
        size="2x" />
    </td>
    <td>
      <a @click.prevent="click"><span class="w-75 d-inline-block">{{ item.basename }}</span><span class="w-25 d-inline-block text-right">
        <font-awesome-icon 
          v-if="edit" 
          icon="edit" />
      </span></a>
    </td>
    <td class="text-right">{{ item.visibility }}</td>
    <td class="text-right">{{ item.size }}</td>
  </tr>
</template>

<script>
    import * as types from '../../store/types';
    import {
        mapActions,
    } from 'vuex';
    export default {
        props: {
            item: {
                type: Object,
                default: null,
            }
        },
        data () {
            return {
                editable: ['txt','php','html','htm','css','js'],
            }
        },
        computed: {
            icon() {
                return this.item.type === 'file' ? 'file-alt' : 'folder'
            },
            checked() {
                return this.item.checked;
            },
            edit() {
                return this.editable.includes(this.item.extension);
            },
        },        
        methods: {
            ...mapActions({
                fetchContents: types.FETCH_CONTENTS,
                changeDirectoryRelative: types.CHANGE_DIRECTORY_RELATIVE
            }),
            toggleFile(file) {
                this.$store.commit(types.TOGGLE_FILE, {
                    file
                })
            },
            click() {
                if (this.item.type === 'file') {
                    if(this.editable.includes(this.item.extension)) {
                        this.fetchContents(this.item.basename);
                    }

                } else {
                    this.changeDirectoryRelative(this.item.basename)
                }
            }
        },
    }
</script>