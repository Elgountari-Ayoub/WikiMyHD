<template>
  <div>
    <RouterView />
  </div>
</template>

<script setup>
import { RouterView } from 'vue-router';
import axios from 'axios';
import { useUsersStore } from './stores/users-store';
import { useUserStore } from './stores/user-store';
import { useSpacesStore } from './stores/spaces-store';
import { useManualsStore } from './stores/manuals-store';

axios.defaults.baseURL = 'http://localhost:8000'
axios.defaults.withCredentials = true;

</script>

<style lang="scss" scoped></style>