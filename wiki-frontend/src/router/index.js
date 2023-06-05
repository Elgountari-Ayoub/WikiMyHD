import { createRouter, createWebHistory } from "vue-router";
import HomeView from "../views/HomeView.vue";
import RegisterView from "../views/RegisterView.vue";
import LoginView from "../views/LoginView.vue";
import DashboardLayout from "../components/layouts/DashboardLayout.vue";
import notFoundView from '../views/notFoundView.vue'

import SpaceIndex from "../views/space/Index.vue";

import ManualIndex from "../views/manual/Index.vue";

import AccountView from "../views/AccountView.vue";
import ProfileSection from "../views/account/ProfileSection.vue";

import UsersIndex from "../views/users/Index.vue";
import { useUserStore } from "../stores/user-store";

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
      beforeEnter: (to, from, next) => {
        const userStore = useUserStore();

        // Check if the user is logged in
        if (userStore.id) {
          // Proceed to the route
          next();
        } else {
          // Redirect to the login page or another route
          next({ name: "login" });
        }
      },
    },
    // Manual route
    {
      path: "/manuals",
      name: "manuals",
      component: ManualIndex,
      props: true,
    },
    // Users management route
    {
      path: "/users",
      name: "users",
      component: UsersIndex,
    },

    // Not Found
    {
      path: "/notFound",
      name: "notFound",
      component: notFoundView,
    },
  ],

  // Portect the routes
});

router.beforeEach(async (to, from, next) => {
  const userStore = useUserStore();
  const notExist = !['home', 'register', 'login', 'profileSection', 'spaces', 'manuals', 'users', 'notFound', 'dashboard', 'articles'].includes(to.name);
  const requiresAuth = !["home", "register", "login"].includes(to.name);
  const requiresAuthoriz = ["users"].includes(to.name);
  const isAuthenticated = userStore.id;
  const isAuthorized = userStore.role == "admin" ? true : false;
  if (requiresAuth && !isAuthenticated) {
    next({ name: "login" });
  } else if (requiresAuthoriz && (!isAuthorized || !isAuthenticated) || notExist) {
    next({ name: "notFound"});
  } else {
    next();
  }
});

export default router;
