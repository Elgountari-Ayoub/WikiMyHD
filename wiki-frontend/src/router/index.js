import { createRouter, createWebHistory } from "vue-router";
import DashboardLayout from "../components/layouts/DashboardLayout.vue";
import HomeView from "../views/HomeView.vue";
import RegisterView from "../views/RegisterView.vue";
import LoginView from "../views/LoginView.vue";

import Spaces from "../views/space/Index.vue";
import Space from "../views/space/Show.vue";

import Manuals from "../views/manual/Index.vue";
import Manual from "../views/manual/Show.vue";

import Articles from "../views/article/Index.vue";
import Article from "../views/article/Show.vue"
import AddArticle from "../views/article/Add.vue"
import EditArticle from "../views/article/Edit.vue"

import AccountView from "../views/AccountView.vue";
import ProfileSection from "../views/account/ProfileSection.vue";

import UsersIndex from "../views/users/Index.vue";
import { useUserStore } from "../stores/user-store";

import notFoundView from '../views/notFoundView.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    // Home
    {
      path: "/",
      name: "home",
      component: HomeView,
    },
    // Register
    {
      path: "/register",
      name: "register",
      component: RegisterView,
    },
    // Login
    {
      path: "/login",
      name: "login",
      component: LoginView,
    },
    // Dashboard
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

    // Spaces routes
    // All Spaces
    {
      path: "/spaces",
      name: "spaces",
      component: Spaces,
      props: true,
    },
    // Space by id
    {
      path: "/spaces/:id",
      name: "space",
      component: Space,
      props: true,
    },



    // Manual route
    // All manuals
    {
      path: "/manuals",
      name: "manuals",
      component: Manuals,
      props: true,
    },
    // Manual by id
    {
      path: "/manuals/:id",
      name: "manual",
      component: Manual,
      props: true,
    },

    // Article route
    // All articles
    {
      path: "/articles",
      name: "articles",
      component: Articles,
      props: true,
    },
    // Article by id
    {
      path: "/articles/:id",
      name: "article",
      component: Article,
      props: true,
    },
    // Create Article
    {
      path: "/addArticle/:space_id/:manual_id",
      name: "addArticle",
      component: AddArticle,
      props: true,
    },
    // Update Article
    {
      path: "/editArticle/:id",
      name: "editArticle",
      component: EditArticle,
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
  const notExist = !['notFound','dashboard', 'home', 'register', 'login', 'users','profileSection', 'spaces', 'space', 'manuals', 'manual', ,'articles', 'article', 'addArticle', 'editArticle'].includes(to.name);
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
