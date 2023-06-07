
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
import { RouterView } from 'vue-router';
import Tiptap from '../../components/editor/Tiptap.vue';


</script>

<style>

</style>
