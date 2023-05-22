import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import RegisterView from '../views/RegisterView.vue'
import LoginView from '../views/LoginView.vue'
import DashboardLayout from '../components/layouts/DashboardLayout.vue'

import SpaceIndex from '../views/space/Index.vue'
import CreateSpaceView from '../views/space/Create.vue'
import editSpaceView from '../views/space/Edit.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: HomeView
    },
    {
      path: '/register',
      name: 'register',
      component: RegisterView
    },
    {
      path: '/login',
      name: 'login',
      component: LoginView
    },
    {
      path: '/dashboard',
      name: 'dashboard',
      component: DashboardLayout
    },


    // spaces routes
    {
      path: '/spaces',
      name: 'spaces',
      component: SpaceIndex
    },
    {
      path: '/spaces/create',
      name: 'createSpace',
      component: CreateSpaceView
    },
    {
      path: '/spaces/edit',
      name: 'editSpace',
      component: editSpaceView
    },



  ]
})

export default router
