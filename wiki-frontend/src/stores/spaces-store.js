import { defineStore } from "pinia";
import axios from "axios";

export const useUserStore = defineStore("user", {
  // arrow function recommended for full type inference
  state: () => ({
    // all these properties will have their type inferred automatically
    id: null,
    id_user: null,
    title: null,
    description: null,
  }),
  actions: {
    async setUserDetails(res) {
      this.$state.id = res.data.id;
      this.$state.id_user = res.data.id_user;
      this.$state.title = res.data.title;
      this.$state.description = res.data.description;
    },
    async fetchUser() {
      try {
        const res = await axios.get("http://localhost:8000/api/spaces");
        this.$state.id = res.data.id;
        this.$state.id_user = res.data.id_user;
        this.$state.title = res.data.title;
        this.$state.description = res.data.description;

      } catch (error) {
        console.log(error);
      }
    },
    clearUser() {
      this.$state.id = null;
      this.$state.id_user = null;
      this.$state.title = null;
      this.$state.description = null;
    },
  },
  persist: true,
});
