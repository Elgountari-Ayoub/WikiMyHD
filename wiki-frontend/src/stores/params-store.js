import { defineStore } from "pinia";
import axios from "axios";

export const useParamsStore = defineStore("paramsStore", {
  state: () => ({
    spaceId: null,
    manualId: null,
    articleId: null,
    spaceTitle: null,
    articleVersion: null,
  }),
  actions: {
    setSpaceId(spaceId) {
      this.$state.spaceId = spaceId;
    },
    setManualId(manualId) {
      this.$state.manualId = manualId;
    },
    setArticleId(articleId) {
      this.$state.articleId = articleId;
    },
    setSpaceTitle(spaceTitle) {
      this.$state.spaceTitle = spaceTitle;
    },
    setArticleVersion(articleVersion) {
      this.$state.articleVersion = articleVersion;
    },

    getSpaceId() {
      return this.$state.spaceId;
    },
    getManualId() {
      return this.$state.manualId;
    },
    getArticleId() {
      return this.$state.articleId;
    },
    getSpaceTitle() {
      return this.$state.spaceTitle;
    },
    getArticleVersion() {
      return this.$state.articleVersion;
    },

    clearSpaceId() {
      this.$state.spaceId = null;
    },
    clearManualId() {
      this.$state.manualId = null;
    },
    clearArticleId() {
      this.$state.articleId = null;
    },
    clearSpaceTitle() {
      this.$state.spaceTitle = null;
    },
    clearArticleVersion() {
      this.$state.articleVersion = null;
    },
    clear() {
      this.$state.spaceId = null;
      this.$state.manualId = null;
      this.$state.articleId = null;
      this.$state.spaceTitle = null;
      this.$state.articleVersion = null;
    },
  },
  persist: true,
});
