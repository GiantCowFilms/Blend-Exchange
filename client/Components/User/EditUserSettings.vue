<template>
    <form v-on:submit.prevent="submit(user,null,form)">
            <ajax-error input="username" />
            <input name="username" v-model="user.username" placeholder="Username" class="txtBlue bodyStack"/>
        <ajax-error input="email" />
        <input name="email" v-model="user.email" class="txtBlue bodyStack" placeholder="Email" />
        <div v-if="use_password">
            Change Password
        </div>
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

export default {
    mixins: [
        AjaxForm
    ],
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
    mounted () {
        this.setEndpoint(`/users/${this.user.id}/update`);
    },
    methods: {
        completedAjaxUpload (data) {
            alert('Your settings were successfully updated!');
        },
    }
}
</script>
