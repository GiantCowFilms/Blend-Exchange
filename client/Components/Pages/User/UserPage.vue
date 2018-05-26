<template>
<main-layout>
    <div slot="content">
        <user-profile :user="user"/>
    </div>
</main-layout>
</template>
<script>
import UserProfile from '@C/User/UserProfile'
import blendExchange from '@/Api/BlendExchangeApi'

export default {
    components: { 
        UserProfile
    },
    async beforeRouteEnter (to, from, next) {
        try {
            var response = await blendExchange.getEndpoint(`/users/${to.params.id}`);
            var user = response.data;
            next(vm => vm.$data.user = user);
        } catch (err) {
            if(!err.response) {
                throw err;
            }
            if(err.response.status == 404) {
                next(new Error('User was not found.'));
            } else {
                next(new Error('An internal error occured retrieving this user profile file.'));
            }
        }
    },
    data() {
        return {
            user: {
                id: 'bah',
                username: 'bah'
            }
        }
    }
}
</script>
