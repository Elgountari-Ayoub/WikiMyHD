import { defineStore } from "pinia";
import axios from "axios";

export const useSpaceIdStore = defineStore("spaceIdStore", {
  state: () => ({
    spaceId: null,
  }),
  actions: {
    setSpaceId(spaceId) {
      this.$state.spaceId =  spaceId
    },
    async getSpaceId() {
      return this.$state.spaceId 
    },
    clearSpaceId() {
      this.$state.spaceId = null;
    },
  },
  persist: true,
});
