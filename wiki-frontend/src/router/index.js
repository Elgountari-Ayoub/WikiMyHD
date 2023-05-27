import { createRouter, createWebHistory } from "vue-router";
import HomeView from "../views/HomeView.vue";
import RegisterView from "../views/RegisterView.vue";
import LoginView from "../views/LoginView.vue";
import DashboardLayout from "../components/layouts/DashboardLayout.vue";

import SpaceIndex from "../views/space/Index.vue";

import ManualIndex from "../views/manual/Index.vue";

import AccountView from "../views/AccountView.vue";
import ProfileSection from "../views/account/ProfileSection.vue";

import UsersIndex from '../views/users/Index.vue'


const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: "/",
      name: "home",
      component: HomeView,
    },
    {
      path: "/register",
      name: "register",
      component: RegisterView,
    },
    {
      path: "/login",
      name: "login",
      component: LoginView,
    },
    {
      path: "/dashboard",
      name: "dashboard",
      component: DashboardLayout,
    },

    // Account Routes
    {
      path: "/account",
      name: "account",
      component: AccountView,
      children: [
        {
          path: "profile",
          name: "profileSection",
          component: ProfileSection,
        },
      ],
    },

    // spaces routes
    {
      path: "/spaces",
      name: "spaces",
      component: SpaceIndex,
    },
    // Manual route
    {
      path: "/manuals",
      name: "manuals",
      component: ManualIndex,
    },
    // Users management route
    {
      path: "/users",
      name: "users",
      component: UsersIndex,
    },

  ],
});

export default router;
