import { defineStore } from "pinia";
import axios from "axios";

export const useSpacesStore = defineStore("spaces", {
  state: () => ({
    spaces: [], // Array to store multiple spaces
  }),
  actions: {
    async setSpacesDetails(spaces) {
      this.$state.spaces = spaces.map((space) => ({
        id: space.id,
        id_user: space.id_user,
        title: space.title,
        description: space.description,
        color: "#" + Math.floor(Math.random() * 16777215).toString(16),
      }));
    },
    async fetchSpaces() {
      try {
        this.clearSpaces();
        const res = await axios.get("http://localhost:8000/api/spaces");
        this.setSpacesDetails(res.data.spaces);
        console.log("STORED SPACES \n\n", this.$state.spaces);
      } catch (error) {
        console.log(error);
      }
    },
    clearSpaces() {
      this.$state.spaces = [];
    },
  },
  persist: true,
});
