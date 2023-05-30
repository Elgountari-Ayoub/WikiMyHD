import { defineStore } from "pinia";
import axios from "axios";
import { useSpaceIdStore } from "./space-id-store";

export const useManualsStore = defineStore("manuals", {
  state: () => ({
    manuals: {
      id: null,
      id_user: null,
      id_space: null,
      title: null,
      description: null,
      space: null,
      color: null,
      space: null,
    },
  }),
  actions: {
    async setManualsDetails(res) {
      try {
        let manuals = res.data.manuals;
        if (Array.isArray(manuals)) {
          this.$state.manuals = manuals.map((manual) => ({
            id: manual.id,
            id_user: manual.id_user,
            id_space: manual.id_space,
            title: manual.title,
            description: manual.description,
            space: manual.space,
            color: "#" + Math.floor(Math.random() * 16777215).toString(16),
            space: manual.space,
          }));
          return true;
        } else {
          console.log("manual not an array\nMANUAL => ", manuals);
          return false;
        }
      } catch (error) {
        console.log("ERROR IN SETTING MANUALS DEATIALS");
      }
    },

    // Fetch all the manuals
    async fetchManuals() {
      await axios
        .get("http://localhost:8000/api/manuals")
        .then((response) => {
          this.setManualsDetails(response);
        })
        .catch((error) => {
          console.log("ERROR IN FETCHING MANUALS", error);
        });
    },

    // Fetch Manual By Space id
    async fetchManualsBySpace(spaceId) {
      console.log('ha response => ',  spaceId);
      if (spaceId) {
        await axios
          .get(`http://localhost:8000/api/manuals/${spaceId}`)
          .then((response) => {
            this.setManualsDetails(response);
            return true;
          })
          .catch((error) => {
            console.log("ERROR IN FETCHING MANUALS BY SPACE", error);
          });
      } else {
        console.log("spaceId has no value");
        return false;
      }
    },
    clearManuals() {
      this.$state.manuals = [];
    },
  },
  persist: true,
});
