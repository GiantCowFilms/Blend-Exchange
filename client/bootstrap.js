import Vue from 'vue'
import App from './App'
import router from './router'
import vmodal from 'vue-js-modal'
import AjaxForm from '@C/Utilities/AjaxForm'
import AjaxButton from '@C/Utilities/AjaxButton'
import AjaxError from '@C/Utilities/AjaxFormError'
import Spinner from '@C/Utilities/Spinner'
import MainLayout from '@/Components/Layout/MainLayout'
import axios from 'axios';
import VueAxios from 'vue-axios';
import store from './Store'
//Plugins
Vue.use(AjaxForm);
Vue.use(AjaxButton);
Vue.use(AjaxError);
Vue.use(MainLayout);
Vue.use(vmodal)
Vue.use(VueAxios, axios);
//Components
Vue.component('main-layout', MainLayout);
Vue.component('spinner', Spinner);

/**
 * Define Vue App
 */
new Vue({
    el: '#dynamicContainer',
    render: h => h(App),
    router,
    store,
    components: { App },
});

