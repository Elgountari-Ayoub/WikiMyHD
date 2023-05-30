import { defineStore } from "pinia";
import axios from "axios";
import { useSpaceIdStore } from "./space-id-store";

export const useSpacesStore = defineStore("spaces", {
  state: () => ({
    spaces: [], // Array to store multiple spaces
  }),
  actions: {
    async setSpacesDetails(res) {
      try {
        console.log('ress => ', res
        );
        let spaces = res.data.spaces;
        if (!Array.isArray(spaces)) {
          console.log(spaces, " => not an array");
          return false;
        }
        if (spaces.length === 0) {
          console.log(
            "spaces array are empty\n spaces array length = ",
            spaces.length
          );
          return false;
        }

        this.$state.spaces = spaces.map((space) => ({
          id: space.id,
          id_user: space.id_user,
          title: space.title,
          description: space.description,
          color: "#" + Math.floor(Math.random() * 16777215).toString(16),
        }));
      } catch (error) {
        console.log("ERROR IN SETTING SPACES DEATIALS");
      }
    },
    async fetchSpaces() {
      await axios
        .get("http://localhost:8000/api/spaces")
        .then((response) => {
          this.setSpacesDetails(response);
        })
        .catch((error) => {
          console.log("ERROR IN FETCHING SPACES", error);
        });
    },
    async fetchSpaceById(spaceId) {
      await axios
        .get(`http://localhost:8000/api/spaces/${spaceId}`)
        .then((response) => {
          this.setSpacesDetails(response);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    clearSpaces() {
      this.$state.spaces = [];
    },
  },
  persist: true,
});
