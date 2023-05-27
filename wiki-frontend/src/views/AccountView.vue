<template>
    <div id="AccountView">
        <RouterView/>
    </div>
</template>

<script setup>
import { RouterView } from 'vue-router';
import ProfileSection from './account/ProfileSection.vue';
import DashboardLayout from '../components/layouts/DashboardLayout.vue';
</script>

<style lang="scss" scoped>

</style>