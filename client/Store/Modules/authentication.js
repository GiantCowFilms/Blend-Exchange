import blendExchange from "@/Api/BlendExchangeApi";
import ls from 'local-storage'
import axios from 'axios'

function authFromStorage() {
    var auth = {};
    try {
        auth.user = ls.get('auth-user');
    } catch (e) {
        auth.user = null;
    }
    auth.token = ls.get('auth-token') || '';
    auth.state = ls.get('auth-state') || 'none';
    return auth;
}

let auth = authFromStorage();

const state = {
    token: auth.token,
    auth_state: auth.state,
    auth_user: auth.user
}

const getters = {
    isAuthenticated (state) {
        return state.auth_state === 'authenticated'
    },
    isAdministrator (state) {
        return Array.isArray(state.auth_user.roles) && state.auth_user.roles.some(v => v === 'admin');
    },
    currentUser (state) {
        return state.auth_user;
    }
}

const actions = {
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
    }
}

export default {
    state,
    getters,
    actions,
    mutations
}