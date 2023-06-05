import { defineStore } from "pinia";
import axios from "axios";
import { useSpaceIdStore } from "./space-id-store";

export const useSpacesStore = defineStore("spaces", {
  state: () => ({
    spaces: [], // Array to store multiple spaces
  }),
  actions: {
    async setSpaces(res) {
      this.$state.spaces = [];
      try {
        let spaces = res.data.spaces;
        if (!Array.isArray(spaces)) {
          return false;
        }
        if (spaces.length === 0) {
          console.log("spaces array length = ", spaces.length);
          return false;
        }

        this.$state.spaces = spaces.map((space) => ({
          id: space.id,
          user_id: space.user_id,
          title: space.title,
          description: space.description,
          color: this.setSpaceColor(space.title.charAt(0)),
          users: space.users,
          manuals: space.manuals,
        }));
      } catch (error) {
        console.log("ERROR IN SETTING SPACES DEATIALS");
        console.log(error);
      }
    },
    async getSpaces() {
      await axios
        .get("/api/spaces")
        .then((response) => {
          console.log("respp => ", response);

          this.setSpaces(response);
        })
        .catch((error) => {
          console.log("ERROR IN getING SPACES", error);
        });
    },
    async getSpaceById(spaceId) {
      await axios
        .get(`/api/spaces/${spaceId}`)
        .then((response) => {
          this.setSpaces(response);
        })
        .catch((error) => {
          console.log(error);
        });
    },
    clearSpaces() {
      this.$state.spaces = [];
    },

    setSpaceColor(letter) {
      let color = '';
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
