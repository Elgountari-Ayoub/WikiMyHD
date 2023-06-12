
<template>
    <RouterView />
    <div>
        <DashboardLayout>
            <div class="min-h-[80vh] flex flex-col ">
                <TipTapShow />
            </div>
        </DashboardLayout>
    </div>
</template>


<script setup>
import DashboardLayout from '../../components/layouts/DashboardLayout.vue';
import { RouterView } from 'vue-router';
import TipTapShow from '../../components/editor/TipTapShow.vue';

</script>
<!-- editor.setEditable(this.editable) -->
<style>
</style>
