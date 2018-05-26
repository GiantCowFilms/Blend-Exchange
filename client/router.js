import Vue from 'vue'
import Router from 'vue-router'
import MainLayout from '@/Components/Layout/MainLayout'
import BlendPage from '@P/Blend/BlendPage'
import BlendIndexPage from '@P/Blend/BlendIndexPage'
import ErrorPage from '@P/ErrorPage'
import Homepage from '@P/Homepage';
import AuthCompletePage from '@P/Auth/AuthCompletePage'
import AuthStatus from '@/Components/Authentication/AuthStatus'
import UserPage from '@P/User/UserPage'
import store from '@/Store/index'
Vue.use(Router)

let router = new Router({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'Homepage',
      component: Homepage
    },
    {
      path: '/blends/',
      name: 'BlendIndexPage',
      component: BlendIndexPage
    },
    {
        path: '/blends/:id',
        name: 'BlendPage2',
        component: BlendPage
    },
    {
        path: '/b/:id',
        name: 'BlendPage',
        component: BlendPage
    },
    {
      path: '/auth/complete',
      name: 'AuthCompletePage',
      component: AuthCompletePage,
      // meta: {
      //   protected: 'guest',
      // }
    },
    {
      path: '/users/current',
      redirect (to) {
        return {
          name: 'UserPage',
          params: {
            id: store.getters.currentUser.id
          }
        }
      }
    },
    {
      path: '/users/:id',
      name: 'UserPage',
      component: UserPage,
    },
    {
      path: '/privacy',
      name: '',
      component: MainLayout
    },
    {
      path: '/terms',
      name: '',
      component: MainLayout
    },
    {
      path: '/about',
      name: '',
      component: MainLayout
    },
    {
      path: '/userscript',
      name: '',
      component: MainLayout
    },
    {
      path: '/contribute',
      name: '',
      component: MainLayout
    },
    {
      path: '/help',
      name: '',
      component: MainLayout
    },
    {
      path: '*',
      name: 'ErrorPage',
      component: ErrorPage,
      props: true
    }
  ]
});

router.beforeEach((to, from, next) => {
  var protection = to.meta.protected;
  let user = store.getters.currentUser;
  console.log(to);
  if (protection === 'auth') {
      if (user === null) {
          window.location = '/auth/redirect';
      } else {
          next();
      }
  }
  if (protection === 'guest') {
      if (user === null) {
          next();
      } else {
          window.location = '/';
      }
  }
  next();
});

router.onError((err) => {
  router.push({
      name: 'ErrorPage',
      params: { error: err.message }
  });
});

export default router;