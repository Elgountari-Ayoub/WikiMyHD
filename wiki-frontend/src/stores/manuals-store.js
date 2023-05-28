import { defineStore } from "pinia";
import axios from "axios";

export const useManualsStore = defineStore("manuals", {
  state: () => ({
    manuals: [], // Array to store multiple manuals
  }),
  actions: {
    async setManualsDetails(manuals) {
      if (Array.isArray(manuals)) {
        this.$state.manuals = manuals.map((manual) => ({
          id: manual.id,
          id_user: manual.id_user,
          id_space: manual.id_space,
          title: manual.title,
          description: manual.description,
          space: manual.space,
          color: "#" + Math.floor(Math.random() * 16777215).toString(16),
        }));
      } else {
      }
    },
    async fetchManuals() {
      try {
        const res = await axios.get("http://localhost:8000/api/manuals");
        this.setManualsDetails(res.data.manuals);
        console.log("STORED MANUALS\n\n", this.$state.manuals);
      } catch (error) {
        console.log(error);
      }
    },
    clearManuals() {
      this.$state.manuals = [];
    },
  },
  persist: true,
});
