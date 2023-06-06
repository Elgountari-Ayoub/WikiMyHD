import { defineStore } from "pinia";
import axios from "axios";

export const useSpaceIdStore = defineStore("spaceIdStore", {
  state: () => ({
    spaceId: null,
    spaceTitle: null,
  }),
  actions: {
    setSpaceId(spaceId) {
      this.$state.spaceId =  spaceId;
    },
    setSpaceTitle(spaceTitle) {
      this.$state.spaceTitle =  spaceTitle;
    },
    async getSpaceId() {
      return this.$state.spaceId 
    },
    async getSpaceId() {
      return this.$state.spaceTitle 
    },
    clearSpaceId() {
      this.$state.spaceId = null;
    },
    clearSpaceId() {
      this.$state.spaceTitle = null;
    },
  },
  persist: true,
});
