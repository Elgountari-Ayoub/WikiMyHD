import { defineStore } from "pinia";
import axios from "axios";

export const useUserStore = defineStore("user", {
  // arrow function recommended for full type inference
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
  }),
  actions: {
    async setUserDetails(res) {
      this.$state.id = res.data.user.id;
      this.$state.name = res.data.user.name;
      this.$state.email = res.data.user.email;
      this.$state.password = res.data.user.password;
      this.$state.photo = res.data.user.photo;
      this.$state.user_name = res.data.user.user_name;
      this.$state.status = res.data.user.status;
      this.$state.role = res.data.user.role;
      this.$state.post = res.data.user.post;
      this.$state.isAdmin = res.data.user.role === "admin" ? true : false;
    },
    async fetchUser() {
      try {
        const res = await axios.get("http://localhost:8000/api/user");
        this.$state.id = res.data.user.id;
        this.$state.name = res.data.user.name;
        this.$state.email = res.data.user.email;
        this.$state.password = res.data.user.password;
        this.$state.photo = res.data.user.photo;
        this.$state.user_name = res.data.user.user_name;
        this.$state.status = res.data.user.status;
        this.$state.role = res.data.user.role;
        this.$state.post = res.data.user.post;

        this.$state.isAdmin = res.data.user.role === "admin" ? true : false;
      } catch (error) {
        console.log(error);
      }
    },
    clearUser() {
      this.$state.id = null;
      this.$state.name = null;
      this.$state.email = null;
      this.$state.password = null;
      (this.$state.photo = null), (this.$state.user_name = null);
      this.$state.status = null;
      this.$state.role = null;
      this.$state.post = null;
      this.$state.isAdmin = false;
    },
  },
  persist: true,
});
