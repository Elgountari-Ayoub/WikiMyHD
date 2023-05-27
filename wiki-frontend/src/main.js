import "./assets/tailwindcss.css";
import "flowbite/dist/flowbite.css";

import { createPinia } from "pinia";

import { createApp } from "vue";
import piniaPluginPersistedstate from "pinia-plugin-persistedstate";

import App from "./App.vue";
import router from "./router";

import VueSweetalert2 from "vue-sweetalert2";
import "sweetalert2/dist/sweetalert2.min.css";

const app = createApp(App);

const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);

app.use(VueSweetalert2);
app.use(pinia);
app.use(router);

app.mount("#app");
