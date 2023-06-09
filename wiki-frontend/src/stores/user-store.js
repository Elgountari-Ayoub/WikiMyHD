import { defineStore } from "pinia";
import axios from "axios";

export const useUserStore = defineStore("user", {
  state: () => ({
    // all these properties will have their type inferred automatically
    id: null,
    name: null,
    email: null,
    password: null,
    photo: null,
    user_name: null,
    status: null,
    role: null,
    post: null,
    isAdmin: false,
    spaces: null,
    manuals: null,
    articles: null,
  }),
  actions: {
    async setUser(res) {
      this.$state.id = res.data.user.id;
      this.$state.name = res.data.user.name;
      this.$state.email = res.data.user.email;
      this.$state.password = res.data.user.password;
      this.$state.photo = res.data.user.photo;
      this.$state.user_name = res.data.user.user_name;
      this.$state.status = res.data.user.status;
      this.$state.role = res.data.user.role;
      this.$state.post = res.data.user.post;
      this.$state.spaces = res.data.user.spaces;
      this.$state.manuals = res.data.user.manuals;
      this.$state.articles = res.data.user.articles;
      this.$state.isAdmin = res.data.user.role === "admin" ? true : false;
    },
    async getUser() {
      try {
        const res = await axios
          .get("/api/users/getauth")
          .then((respnose) => {
            this.$state.id = respnose.data.user.id;
            this.$state.name = respnose.data.user.name;
            this.$state.email = respnose.data.user.email;
            this.$state.password = respnose.data.user.password;
            this.$state.photo = respnose.data.user.photo;
            this.$state.user_name = respnose.data.user.user_name;
            this.$state.status = respnose.data.user.status;
            this.$state.role = respnose.data.user.role;
            this.$state.post = respnose.data.user.post;
            this.$state.spaces = respnose.data.user.spaces;
            this.$state.manuals = respnose.data.user.manuals;
            this.$state.articles = respnose.data.user.articles;

            this.$state.isAdmin =
              respnose.data.user.role === "admin" ? true : false;

            return true;
          })
          .catch((error) => {
            console.log(
              "FAILED IN GETTING THE USER!,The ERROR MESSAGE => ",
              error.message
            );
            return false;
          });
      } catch (error) {
        return false;
      }
    },
    clearUser() {
      this.$state.id = null;
      this.$state.name = null;
      this.$state.email = null;
      this.$state.password = null;
      this.$state.photo = null;
      this.$state.user_name = null;
      this.$state.status = null;
      this.$state.role = null;
      this.$state.post = null;
      this.$state.spaces = null;
      this.$state.manuals = null;
      this.$state.articles = null;
      this.$state.isAdmin = false;
    },
  },
  persist: true,
});
