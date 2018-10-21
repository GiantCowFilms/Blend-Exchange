<template>
    <form v-on:submit.prevent="submit(userSettings,null,form)">
            <ajax-error input="username" />
            <input name="username" v-model="userSettings.username" placeholder="Username" class="txtBlue bodyStack"/>
        <ajax-error input="email" />
        <input name="email" v-model="userSettings.email" class="txtBlue bodyStack" placeholder="Email" />
        <!--<div v-if="use_password">
            Change Password
        </div>-->
        <div class='btnBlue' v-on:click="$modal.hide('editSettings')">
            Cancel
        </div>
        <button class='btnBlue'>
            Save
        </button>
    </form>
</template>
<script>
import AjaxForm from '@/Mixins/AjaxForm'
import clonedeep from 'clone-deep'

export default {
    mixins: [
        AjaxForm
    ],
    data () {
        return {
            userSettings: null
        };
    },
    props: {
        user: {
            type: Object,
            required: true
        }
    },
    computed: {
        use_password () {
            return this.user.account_type === 'password';
        }
    },
    beforeMount () {
      this.$data.userSettings = clonedeep(this.$props.user);
    },
    mounted () {
        this.setEndpoint(`/users/${this.user.id}/update`);
    },
    methods: {
        completedAjaxUpload (data) {
            this.$store.dispatch('UPDATE_USER',this.userSettings);
            this.$modal.hide("editSettings");
            alert('Your settings were successfully updated!');
        },
    }
}
</script>
