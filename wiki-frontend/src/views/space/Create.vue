<template>
    <div>
        <DashboardLayout>
            <div class="container flex flex-col px-4 py-8 mx-auto mt-14 place-items-center">
                <h1 class="mb-4 text-2xl font-semibold">Add a Space</h1>
                <form @submit.prevent="submitForm" class="max-w-md">
                    <div class="mb-4">
                        <label for="title" class="block mb-2 font-medium text-gray-700">Title</label>
                        <input v-model="title" type="text" id="title" name="title"
                            class="w-full px-4 py-2 border-gray-300 rounded-md" placeholder="Enter a title" required>
                    </div>
                    <div class="mb-4">
                        <label for="description" class="block mb-2 font-medium text-gray-700">Description</label>
                        <textarea v-model="description" id="description" name="description" rows="4"
                            class="w-full px-4 py-2 border-gray-300 rounded-md" placeholder="Enter a description"
                            required></textarea>
                    </div>
                    <div>
                        <button type="submit"
                            class="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600">Submit</button>
                    </div>
                </form>
            </div>
        </DashboardLayout>
    </div>
</template>
<script setup>
import DashboardLayout from '../../components/layouts/DashboardLayout.vue';
import SearchInput from '../../components/Partials/SearchInput.vue';

import { ref } from 'vue';
import axios from 'axios';

const title = ref('');
const description = ref('');


const submitForm = async () => {
    try {
        const response = await axios.post('http://localhost:8000/api/spaces', {
            title: title.value,
            description: description.value
        });

        // Handle the response here if needed
        console.log(response.data);

        // Reset form fields after successful submission
        title.value = '';
        description.value = '';
    } catch (error) {
        // Handle the error here if needed
        console.error(error);
    }
};
</script>

  
<style>/* Add your custom styles here if needed */</style>
  