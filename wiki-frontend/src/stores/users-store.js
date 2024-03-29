import { defineStore } from "pinia";
import axios from "axios";

export const useUsersStore = defineStore("users", {
  state: () => ({
    users: [], // Array to store multiple users
  }),
  actions: {
    async setUsers(res) {
      let users = res.data.users
      this.$state.users = users.map((user) => ({
        id: user.id,
        name: user.name,
        email: user.email,
        password: user.password,
        photo: user.photo,
        user_name: user.user_name,
        status: user.status,
        role: user.role,
        post: user.post,
        spaces: user.spaces,
        manuals: user.manuals,
      }));
    },
    async getUsers() {
      try {
        const res = await axios.get("/api/users");
        this.setUsers(res);
        return true;
      } catch (error) {
        return false;
      }
    },
    clearUsers() {
      this.$state.users = [];
    },
  },
  persist: true,
});
