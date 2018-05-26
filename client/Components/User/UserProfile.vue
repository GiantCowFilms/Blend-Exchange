<template>
    <div>
        <modal name='editSettings' classes='modal' :pivotY='0.2' height="auto">
            <h2>Edit User</h2>
            <edit-user-settings :user="user"/>
        </modal>
        <h2>{{ user.username }}</h2> <a v-on:click="$modal.show('editSettings')">Edit Settings</a>
        <div>
        
        </div>
        <h2>{{ user.username }}'s Blends</h2>
        <index-blends :query="query"></index-blends>
    </div>
</template>
<script>
import EditUserSettings from '@C/User/EditUserSettings'
import IndexBlends from '@C/Blend/IndexBlends'

export default {
  data () {
    return {

    }
  },
  computed: {
      query() {
            return {
                sort: 'downloads',
                page: 1,
                owner: this.user.id
            }
      }
  },
  props: {
    user: {
      required: true,
      type: Object
    }
  },
  components: {
      EditUserSettings,
      IndexBlends
  }
};
</script>
