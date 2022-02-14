import Vue from 'vue'
import Router from 'vue-router'
import Home from '../views/Home'
import Welcome from '../views/Welcome'
import NotFound from '../views/NotFound'
import AccessDenied from '../views/AccessDenied'
import Profile from '../views/Profile'
import Legal from '../views/Legal'
import Dev from '../views/Dev'

// Users
import Users from '../views/users/Index'
import UsersCreate from '../views/users/Create'
import UsersEdit from '../views/users/Edit'

// Cripto
import Debts from '../views/debts/Index'

const originalPush = Router.prototype.push
Router.prototype.push = function push(location) {
  return originalPush.call(this, location).catch(err => {
    if (err.name !== 'NavigationDuplicated') throw err
  })
}

Vue.use(Router)

export default new Router({
  mode: 'history',
  routes: [
    {
      path: '*',
      component: NotFound
    },
    {
      path: '/accessd_denied',
      name: 'accessd_denied',
      component: AccessDenied
    },
    {
      path: '/',
      name: 'home',
      component: Home,
      meta: {
        rol: ['not_authorized'],
        redirect: 'welcome'
      }
    },
    {
      path: '/welcome',
      name: 'welcome',
      component: Welcome,
      meta: {
        permission: 'authenticated',
        redirect: 'home'
      }
    },
    {
      path: '/perfil',
      name: 'perfil',
      component: Profile,
      meta: {
        permission: 'authenticated',
        redirect: 'home'
      }
    },
    {
      path: '/legal',
      name: 'legal',
      component: Legal
    },
    {
      path: '/dev',
      name: 'dev',
      component: Dev,
      meta: {
        permission: 'authenticated',
        redirect: 'home'
      }
    },

    // Cripto
    {
      path: '/debts',
      name: 'debts',
      component: Debts,
      meta: {
        rol: ['admin', 'owner'],
        redirect: 'welcome'
      }
    },

    // Users
    {
      path: '/users',
      name: 'users',
      component: Users,
      meta: {
        rol: ['admin'],
        redirect: 'welcome'
      }
    },
    {
      path: '/users/nuevo',
      name: 'users_create',
      component: UsersCreate,
      meta: {
        rol: ['admin'],
        redirect: 'welcome'
      }
    },
    {
      path: '/users/editar/:currentUser',
      name: 'users_edit',
      component: UsersEdit,
      props: true,
      meta: {
        rol: ['admin'],
        redirect: 'welcome'
      }
    }
  ],

  scrollBehavior() {
    return { x: 0, y: 0 }
  }
})
