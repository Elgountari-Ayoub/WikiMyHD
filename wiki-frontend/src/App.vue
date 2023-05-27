<template>
  <div>
    <RouterView/>
  </div>
</template>

<script setup>
import {RouterView} from 'vue-router';
import { useUserStore } from './stores/user-store';

import axios from 'axios';
axios.defaults.baseURL = 'http://localhost:8000/api'
</script>

<style lang="scss" scoped>

</style>