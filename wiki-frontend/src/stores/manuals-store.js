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
          title: manual.title,
          description: manual.description,
          
          users: manual.users,
          space: manual.space,
          articles: manual.articles,

          color: this.setManualColor(manual.title.charAt(0)),
        }));
        return true;
      } catch (error) {
        console.log("ERROR IN SETTING MANUALS DEATIALS");
        console.log(error);
      }
    },

    // GEt all the manuals
    async getManuals() {
      this.$state.manuals = [];
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
      this.$state.manuals = [];
      if (spaceId) {
        await axios
          .get(`/api/manuals/${spaceId}`)
          .then((response) => {
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
      let color = '';
      if (letter == "A" || letter == "a") {
        color = "#800000";  // Dark red
      } else if (letter == "B" || letter == "b") {
        color = "#006400";  // Dark green
      } else if (letter == "C" || letter == "c") {
        color = "#000080";  // Dark blue
      } else if (letter == "D" || letter == "d") {
        color = "#808000";  // Olive
      } else if (letter == "E" || letter == "e") {
        color = "#800080";  // Purple
      } else if (letter == "F" || letter == "f") {
        color = "#008080";  // Teal
      } else if (letter == "J" || letter == "j") {
        color = "#000000";  // Black
      } else if (letter == "H" || letter == "h") {
        color = "#800080";  // Purple
      } else if (letter == "I" || letter == "i") {
        color = "#800000";  // Dark red
      } else if (letter == "G" || letter == "g") {
        color = "#008000";  // Green
      } else if (letter == "K" || letter == "k") {
        color = "#000080";  // Dark blue
      } else if (letter == "L" || letter == "l") {
        color = "#808000";  // Olive
      } else if (letter == "M" || letter == "m") {
        color = "#800080";  // Purple
      } else if (letter == "N" || letter == "n") {
        color = "#008080";  // Teal
      } else if (letter == "O" || letter == "o") {
        color = "#808080";  // Gray
      } else if (letter == "P" || letter == "p") {
        color = "#FFA500";  // Orange
      } else if (letter == "Q" || letter == "q") {
        color = "#FFC0CB";  // Pink
      } else if (letter == "R" || letter == "r") {
        color = "#800080";  // Purple
      } else if (letter == "S" || letter == "s") {
        color = "#006400";  // Dark green
      } else if (letter == "T" || letter == "t") {
        color = "#A52A2A";  // Brown
      } else if (letter == "U" || letter == "u") {
        color = "#FF0000";  // Red
      } else if (letter == "V" || letter == "v") {
        color = "#8B4513";  // Saddle brown
      } else if (letter == "W" || letter == "w") {
        color = "#FFD700";  // Gold
      } else if (letter == "X" || letter == "x") {
        color = "#4682B4";  // Steel blue
      } else if (letter == "Y" || letter == "y") {
        color = "#4B0082";  // Indigo
      } else if (letter == "Z" || letter == "z") {
        color = "#2F4F4F";  // Dark slate gray
      }
      return color;
    }
    
  },
  persist: true,
});
