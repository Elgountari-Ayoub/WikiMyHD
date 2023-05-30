import { defineStore } from "pinia";
import axios from "axios";

export const useUsersStore = defineStore("users", {
  state: () => ({
    users: [], // Array to store multiple users
  }),
  actions: {
    async setUsersDetails(users) {
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
      }));
    },
    async fetchUsers() {
      try {
        const res = await axios.get("http://localhost:8000/api/users");

        this.setUsersDetails(res.data.users);
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
