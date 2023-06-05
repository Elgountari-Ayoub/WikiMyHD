import { defineStore } from "pinia";
import axios from "axios";
import { useSpaceIdStore } from "./space-id-store";

export const useManualsStore = defineStore("manuals", {
  state: () => ({
    manuals: [],
  }),
  actions: {
    async setManuals(res) {
      this.$state.manuals = [];
      try {
        let manuals = res.data.manuals;
        if (!Array.isArray(manuals)) {
          console.log(manuals, " => not an array");
          return false;
        }
        if (manuals.length === 0) {
          console.log("manuals array length = ", manuals.length);
          return false;
        }
        this.$state.manuals = manuals.map((manual) => ({
          id: manual.id,
          user_id: manual.user_id,
          space_id: manual.space_id,
          title: manual.title,
          description: manual.description,
          color: this.setManualColor(manual.title.charAt(0)),
          space: manual.space,
          users: manual.users,
        }));
        return true;
      } catch (error) {
        console.log("ERROR IN SETTING MANUALS DEATIALS");
        console.log(error);
      }
    },

    // GEt all the manuals
    async getManuals() {
      await axios
        .get("/api/manuals")
        .then((response) => {
          this.setManuals(response);
        })
        .catch((error) => {
          console.log("error man", error);
          console.log("ERROR IN getING MANUALS", error);
        });
    },

    // get Manual By Space id
    async getManualsBySpace(spaceId) {
      if (spaceId) {
        await axios
          .get(`/api/manuals/${spaceId}`)
          .then((response) => {
            console.log(response);
            this.setManuals(response);
            return true;
          })
          .catch((error) => {
            console.log("err", error);
            console.log("ERROR IN getING MANUALS BY SPACE", error);
          });
      } else {
        console.log("spaceId has no value");
        return false;
      }
    },
    clearManuals() {
      this.$state.manuals = [];
    },
    setManualColor(letter) {
      let color = "";
      if (letter == "A" || letter == "a") {
        color = "#FF0000";
      } else if (letter == "B" || letter == "b") {
        color = "#00FF00";
      } else if (letter == "C" || letter == "c") {
        color = "#0000FF";
      } else if (letter == "D" || letter == "d") {
        color = "#FFFF00";
      } else if (letter == "E" || letter == "e") {
        color = "#FF00FF";
      } else if (letter == "F" || letter == "f") {
        color = "#00FFFF";
      } else if (letter == "J" || letter == "j") {
        color = "#000000";
      } else if (letter == "H" || letter == "h") {
        color = "#800080";
      } else if (letter == "I" || letter == "i") {
        color = "#800000";
      } else if (letter == "G" || letter == "g") {
        color = "#008000";
      } else if (letter == "K" || letter == "k") {
        color = "#000080";
      } else if (letter == "L" || letter == "l") {
        color = "#808000";
      } else if (letter == "M" || letter == "m") {
        color = "#800080";
      } else if (letter == "N" || letter == "n") {
        color = "#008080";
      } else if (letter == "O" || letter == "o") {
        color = "#808080";
      } else if (letter == "P" || letter == "p") {
        color = "#FFA500";
      } else if (letter == "Q" || letter == "q") {
        color = "#FFC0CB";
      } else if (letter == "R" || letter == "r") {
        color = "#800080";
      } else if (letter == "S" || letter == "s") {
        color = "#00FF00";
      } else if (letter == "T" || letter == "t") {
        color = "#A52A2A";
      } else if (letter == "U" || letter == "u") {
        color = "#FF0000";
      } else if (letter == "V" || letter == "v") {
        color = "#8B4513";
      } else if (letter == "W" || letter == "w") {
        color = "#FFD700";
      } else if (letter == "X" || letter == "x") {
        color = "#4682B4";
      } else if (letter == "Y" || letter == "y") {
        color = "#4B0082";
      } else if (letter == "Z" || letter == "z") {
        color = "#2F4F4F";
      }
      return color;
    },
  },
  persist: true,
});
