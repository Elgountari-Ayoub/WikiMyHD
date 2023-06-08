
<template>
    <RouterView />
    <div>
        <DashboardLayout>
            <!-- <div class="fixed bg-white h-16 top-10 z-40 w-[75rem]"></div> -->
            <div class="min-h-[80vh] flex flex-col ">
                <tiptap />
                <button class="bg-green-500 text-white px-4 py-2 rounded-full m-auto w-fit mt-2">Submit Article</button>
            </div>
        <!-- <div class="menu">
                Lorem ipsum dolor sit amet consectetur adipisicing elit. Quam mollitia iste eaque sed quo. Ea cum dolore
                nisi omnis quia, reprehenderit id delectus reiciendis assumenda laborum aspernatur non laboriosam at.
            </div>
            <div class="content">
                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quae odit aspernatur ab consectetur
                accusamus
                nisi modi, quibusdam, libero voluptatum tempore eveniet iure. Dolores est cumque expedita incidunt
                sapiente ad debitis!
                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quae odit aspernatur ab consectetur
                accusamus
                nisi modi, quibusdam, libero voluptatum tempore eveniet iure. Dolores est cumque expedita incidunt
                sapiente ad debitis!
                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quae odit aspernatur ab consectetur
                accusamus
                nisi modi, quibusdam, libero voluptatum tempore eveniet iure. Dolores est cumque expedita incidunt
                sapiente ad debitis!
                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quae odit aspernatur ab consectetur
                accusamus
                nisi modi, quibusdam, libero voluptatum tempore eveniet iure. Dolores est cumque expedita incidunt
                sapiente ad debitis!
                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quae odit aspernatur ab consectetur
                accusamus
                nisi modi, quibusdam, libero voluptatum tempore eveniet iure. Dolores est cumque expedita incidunt
                sapiente ad debitis!
                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quae odit aspernatur ab consectetur
                accusamus
                nisi modi, quibusdam, libero voluptatum tempore eveniet iure. Dolores est cumque expedita incidunt
                sapiente ad debitis!
                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quae odit aspernatur ab consectetur
                accusamus
                nisi modi, quibusdam, libero voluptatum tempore eveniet iure. Dolores est cumque expedita incidunt
                sapiente ad debitis!
                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quae odit aspernatur ab consectetur
                accusamus
                nisi modi, quibusdam, libero voluptatum tempore eveniet iure. Dolores est cumque expedita incidunt
                sapiente ad debitis!
                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quae odit aspernatur ab consectetur
                accusamus
                nisi modi, quibusdam, libero voluptatum tempore eveniet iure. Dolores est cumque expedita incidunt
                sapiente ad debitis!
                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quae odit aspernatur ab consectetur
                accusamus
                nisi modi, quibusdam, libero voluptatum tempore eveniet iure. Dolores est cumque expedita incidunt
                sapiente ad debitis!
                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quae odit aspernatur ab consectetur
                accusamus
                nisi modi, quibusdam, libero voluptatum tempore eveniet iure. Dolores est cumque expedita incidunt
                sapiente ad debitis!
                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quae odit aspernatur ab consectetur
                accusamus
                nisi modi, quibusdam, libero voluptatum tempore eveniet iure. Dolores est cumque expedita incidunt
                sapiente ad debitis!
                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quae odit aspernatur ab consectetur
                accusamus
                nisi modi, quibusdam, libero voluptatum tempore eveniet iure. Dolores est cumque expedita incidunt
                sapiente ad debitis!
                Lorem, ipsum dolor sit amet consectetur adipisicing elit. Quae odit aspernatur ab consectetur
                accusamus
                nisi modi, quibusdam, libero voluptatum tempore eveniet iure. Dolores est cumque expedita incidunt
                sapiente ad debitis!
                </div> -->
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
