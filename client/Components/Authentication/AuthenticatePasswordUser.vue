<template>
<div style="max-width: 480px; margin: auto;">
    <form name="authenticateUser" id="loginForm" v-on:submit.prevent="submit(credentials, null, form)" >
            <div v-if="form.serverError" class="noticeWarning nwDanger bodyStack">
                {{ form.serverError }}
            </div>
            <h1>Sign in</h1>
            <ajax-error input="password"></ajax-error>
            <input v-model="credentials.password" class="txtBlue bodyStack" placeholder="Password" type="password"/>
            <button type="submit" class="btnBlue bodyStack">
                Continue
            </button>
    </form>
</div>
</template>
<script>
import AjaxForm from '@/Mixins/AjaxForm'
import store from '@/Store/index'
export default {
    mixins: [AjaxForm],
    props: {
        token: {
            required: true,
            type: String
        }
    },
    data () {
        return {
            credentials: {}
        };
    },
    mounted () {
        this.setEndpoint(`/auth/token`);
        this.setAuthentication(this.token);
    },
    methods: {
        completedAjaxUpload (data) {
            store.dispatch('LOGIN',data);
            this.$router.push({
                name: 'UserPage',
                params: {
                    id: data.user.id
                }
            });
        }
    }
}
</script>
    