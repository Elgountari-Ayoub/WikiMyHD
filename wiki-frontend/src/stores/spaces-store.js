import { defineStore } from "pinia";
import axios from "axios";
import { useSpaceIdStore } from "./space-id-store";

export const useSpacesStore = defineStore("spaces", {
  state: () => ({
    spaces: [], // Array to store multiple spaces
  }),
  actions: {
    async setSpacesDetails(spaces) {
      if (Array.isArray(manuals)) {
        this.$state.spaces = spaces.map((space) => ({
          id: space.id,
          id_user: space.id_user,
          title: space.title,
          description: space.description,
          color: "#" + Math.floor(Math.random() * 16777215).toString(16),
        }));
      } else {
        console.log(spaces);
        console.log('not an array');
      }
    },
    async fetchSpaces() {
      try {
        const res = await axios.get("http://localhost:8000/api/spaces");
        console.log(res.data.spaces);
        this.setSpacesDetails(res.data.spaces);
      } catch (error) {
        console.log(error);
      }
    },
    async fetchSpaceById(spaceId) {
      try {
        const res = await axios.get(
          `http://localhost:8000/api/spaces/${spaceId}`
        );
        this.setSpacesDetails(res.data.space);
        // console.log("STORED SPACES \n\n", this.$state.spaces);
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
