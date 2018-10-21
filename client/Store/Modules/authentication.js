import blendExchange from "@/Api/BlendExchangeApi";
import ls from 'local-storage'
import axios from 'axios'

const state = {
    token: '',
    auth_state: 'none',
    auth_user: null,
}

const getters = {
    isAuthenticated (state) {
        return state.auth_state === 'authenticated';
    },
    isAdministrator (state) {
        return Array.isArray(state.auth_user.roles) && state.auth_user.roles.some(v => v === 'admin');
    },
    currentUser (state) {
        return state.auth_user;
    }
}

const actions = {
    async ['AUTH_FROM_STORAGE'] (context) {
        var auth = {};
        auth.token = ls.get('auth-token') || '';
        auth.state = ls.get('auth-state') || 'none';
        auth.user = ls.get('auth-user') || {};
        context.commit('SET_AUTH',auth);
        try {
            let response = await axios.get('/api/users/current',{
                headers: {
                    Authorization: `Bearer ${auth.token}`
                }
            });
            auth.user = response.data.data;
            context.commit('SET_AUTH',auth);
        } catch(err) {
            context.commit('SET_AUTH',{
                token: '',
                state: 'none',
                user: {}
            });
            console.log('Currently logged out.')
        }
    },
    async ['LOGIN'] (context,auth) {
        context.commit('SET_AUTH',auth);
    },
    async ['UPGRADE_TOKEN'] (context,setup_token) {
        let result = await blendExchange.getResource({ 
            endpoint: '/auth/token',
            meta: {
                token: setup_token
            }
        });
        context.commit('SET_AUTH',result.data);
    },
    async ['LOGOUT'] (context) {
        context.commit('SET_AUTH',{
            token: '',
            state: 'none',
            user: null
        })
    },
    async ['UPDATE_USER'] (context,newUser) {
        context.commit('SET_USER',newUser);
    }
}

const mutations = {
    ['SET_AUTH'] (state,auth) {
        ls.set('auth-token',auth.token);
        ls.set('auth-user',auth.user);
        ls.set('auth-state','authenticated');
        state.token = auth.token;
        state.auth_state = 'authenticated';
        state.auth_user = auth.user;
        axios.defaults.headers.common['Authorization'] = `Bearer ${auth.token}`;
    },
    ['SET_USER'] (state,user) {
        state.auth_user = user;
        ls.set('auth-user',user);
    }
}

export default {
    state,
    getters,
    actions,
    mutations
}