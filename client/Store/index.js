import Vue from 'vue'
import Vuex from 'vuex'
import auth from '@/Store/Modules/authentication'
import users from '@/Store/Modules/users'
import blends from '@/Store/Modules/blends'
import ls from 'local-storage'

Vue.use(Vuex);

const debug = process.env.NODE_ENV !== 'production'

let store = new Vuex.Store({
  modules: {
    blends,
    users,
    auth
  },
  strict: debug
});

//Auto login
store.dispatch('AUTH_FROM_STORAGE');
ls.on('auth_state',() => {
  store.dispatch('AUTH_FROM_STORAGE');
});

export default store;