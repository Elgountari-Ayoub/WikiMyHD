
<template>
    <RouterView />
    <div>
        <DashboardLayout>
            <tiptap  />
        </DashboardLayout>
    </div>
</template>


<script setup>
import DashboardLayout from '../../components/layouts/DashboardLayout.vue';
import { ref, watchEffect, onMounted, watch } from 'vue';
import axios from 'axios';
import { RouterView, useRouter } from 'vue-router';
import Tiptap from '../../components/editor/Tiptap.vue';

axios.defaults.withCredentials = true;


</script>

<style>

</style>
