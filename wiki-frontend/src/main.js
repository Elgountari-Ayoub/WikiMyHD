import "./assets/tailwindcss.css";
import "flowbite/dist/flowbite.css";
import { createApp } from "vue";
import App from "./App.vue";


import { createPinia } from "pinia";
import piniaPluginPersistedstate from "pinia-plugin-persistedstate";


const pinia = createPinia();
pinia.use(piniaPluginPersistedstate);

const app = createApp(App);

app.use(pinia);

import router from "./router";
app.use(router);


import VueSweetalert2 from "vue-sweetalert2";
import "sweetalert2/dist/sweetalert2.min.css";
app.use(VueSweetalert2);



app.mount("#app");
