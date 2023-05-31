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
      user: null,
    },
  }),
  actions: {
    async setManualsDetails(res) {
      this.$state.manuals = [];
      try {
        let manuals = res.data.manuals;
        if (!Array.isArray(manuals)) {
          console.log(manuals, " => not an array");
          return false;
        }
        if (manuals.length === 0) {
          console.log(
            "manuals array length = ",
            manuals.length
          );
          return false;
        }
        this.$state.manuals = manuals.map((manual) => ({
          id: manual.id,
          id_user: manual.id_user,
          id_space: manual.id_space,
          title: manual.title,
          description: manual.description,
          space: manual.space,
          color: "#" + Math.floor(Math.random() * 16777215).toString(16),
          space: manual.space,
          user: manual.user,
        }));
        return true;
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
          console.log("error man", error);
          console.log("ERROR IN FETCHING MANUALS", error);
        });
    },

    // Fetch Manual By Space id
    async fetchManualsBySpace(spaceId) {
      if (spaceId) {
        await axios
          .get(`http://localhost:8000/api/manuals/${spaceId}`)
          .then((response) => {
            this.setManualsDetails(response);
            console.log("ers", res);
            return true;
          })
          .catch((error) => {
            console.log("err", error);
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
