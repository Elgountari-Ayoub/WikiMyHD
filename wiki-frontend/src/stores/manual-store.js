import { defineStore } from "pinia";
import axios from "axios";
import { useSpaceIdStore } from "./space-id-store";

export const useManualStore = defineStore("manual", {
  state: () => ({
    id: null,
    title: null,
    description: null,

    users: [],
    space: null,
    articles: [],
    color: null,
  }),
  actions: {
    async setManual(res) {
      try {
        let manual = res.data.manual;
        this.$state.id = manual.id;
        this.$state.title = manual.title;
        this.$state.description = manual.description;

        this.$state.users = manual.users;
        this.$state.space = manual.space;
        this.$state.articles = manual.articles;

        this.$state.color = this.setManualColor(manual.title.charAt(0));
      } catch (error) {
        console.log("ERROR IN SETTING MANUALS DEATIALS");
        console.log(error);
      }
    },
    // get Manual By  id
    async getManual(manualId) {
      if (manualId) {
        await axios
          .get(`/api/manuals/${manualId}/show`)
          .then((response) => {
            this.setManual(response);
          })
          .catch((error) => {
            // window.location.href = "/notFound";
            console.log("Can't get the manual", error);
          });
      } else {
        console.log("spaceId has no value");
      }
    },
    clearManual() {
      this.$state.id = null;
      this.$state.title = null;
      this.$state.description = null;
      this.$state.users = [];
      this.$state.space = null;
      this.$state.articles = [];

      this.$state.color = null;
    },
    setManualColor(letter) {
      let color = "";
      if (letter == "A" || letter == "a") {
        color = "#800000"; // Dark red
      } else if (letter == "B" || letter == "b") {
        color = "#006400"; // Dark green
      } else if (letter == "C" || letter == "c") {
        color = "#000080"; // Dark blue
      } else if (letter == "D" || letter == "d") {
        color = "#808000"; // Olive
      } else if (letter == "E" || letter == "e") {
        color = "#800080"; // Purple
      } else if (letter == "F" || letter == "f") {
        color = "#008080"; // Teal
      } else if (letter == "J" || letter == "j") {
        color = "#000000"; // Black
      } else if (letter == "H" || letter == "h") {
        color = "#800080"; // Purple
      } else if (letter == "I" || letter == "i") {
        color = "#800000"; // Dark red
      } else if (letter == "G" || letter == "g") {
        color = "#008000"; // Green
      } else if (letter == "K" || letter == "k") {
        color = "#000080"; // Dark blue
      } else if (letter == "L" || letter == "l") {
        color = "#808000"; // Olive
      } else if (letter == "M" || letter == "m") {
        color = "#800080"; // Purple
      } else if (letter == "N" || letter == "n") {
        color = "#008080"; // Teal
      } else if (letter == "O" || letter == "o") {
        color = "#808080"; // Gray
      } else if (letter == "P" || letter == "p") {
        color = "#FFA500"; // Orange
      } else if (letter == "Q" || letter == "q") {
        color = "#FFC0CB"; // Pink
      } else if (letter == "R" || letter == "r") {
        color = "#800080"; // Purple
      } else if (letter == "S" || letter == "s") {
        color = "#006400"; // Dark green
      } else if (letter == "T" || letter == "t") {
        color = "#A52A2A"; // Brown
      } else if (letter == "U" || letter == "u") {
        color = "#FF0000"; // Red
      } else if (letter == "V" || letter == "v") {
        color = "#8B4513"; // Saddle brown
      } else if (letter == "W" || letter == "w") {
        color = "#FFD700"; // Gold
      } else if (letter == "X" || letter == "x") {
        color = "#4682B4"; // Steel blue
      } else if (letter == "Y" || letter == "y") {
        color = "#4B0082"; // Indigo
      } else if (letter == "Z" || letter == "z") {
        color = "#2F4F4F"; // Dark slate gray
      }      else{
        color = "#2F4F4F";
      }
      return color;
    },
  },
  persist: true,
});
