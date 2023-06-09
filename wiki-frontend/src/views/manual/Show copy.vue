
<template>
    <RouterView />
    <div>
        <DashboardLayout>
            <div class="min-h-[80vh] flex flex-col ">
                <tiptap />
                <!-- <button class="bg-green-500 text-white px-4 py-2 rounded-full m-auto w-fit mt-2">Submit Article</button> -->
            </div>
        </DashboardLayout>
    </div>
</template>


<script setup>
import DashboardLayout from '../../components/layouts/DashboardLayout.vue';
import { RouterView } from 'vue-router';
import Tiptap from '../../components/editor/Tiptap.vue';
</script>

<style>
.menu {
    background-color: black;
    color: white;
}

.content {
    background-color: gray;
    height: 100px;
    overflow: scroll;

}
</style>
