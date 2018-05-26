<template>
    <main-layout>
        <div slot="content">
            <div v-if="!existingUser">
                <create-user :user='user' :token='token'/>
            </div>
            <div v-else-if="needsPassword">
                <authenticate-password-user :token='token'/>
            </div>
        </div>
    </main-layout>
</template>
<script>
import CreateUser from '@/Components/Authentication/CreateUser'
import AuthenticatePasswordUser from '@/Components/Authentication/AuthenticatePasswordUser'
import store from '@/Store/index'

import blendExchange from '@/Api/BlendExchangeApi'

export default {
    data: function () {
        return {
            user: {},
            token: ''
        };
    },
    components: {
        CreateUser,
        AuthenticatePasswordUser
    },
    computed: {
        existingUser: function () {
            return this.user.account_type !== 'partial';
        },
        needsPassword: function () {
            return this.user.account_type === 'password';
        }
    },
    async beforeRouteEnter (to, from, next) {
        try {
            var response = await blendExchange.getEndpoint(`/auth/setup_token`,{
                params: {
                    code: to.query.code
                }
            });
            
            if (response.data.account_type === 'external') {
                store.dispatch('UPGRADE_TOKEN',response.meta.modification_token);
                next(vm => {
                    vm.$router.push({
                        name: 'UserPage',
                        params: {
                            id: response.data.id
                        }
                    });
                })
            }

            next(vm => {
                vm.$data.user = response.data;
                vm.$data.token = response.meta.modification_token
            });

        } catch (err) {
            next(new Error('Something did not work. Try logging in again'));
        }
    }
};
</script>
