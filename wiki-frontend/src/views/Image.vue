<template>
    <div>
        <form @submit.prevent="uploadImage" enctype="multipart/form-data">
            <!-- <input type="file" ref="fileInput" accept="image/*" /> -->
            <div class="mb-6">
                <label for="photo" class="block text-gray-700">Photo:</label>
                <input type="file" id="photo" @change="handlePhotoChange" ref="photoInput"
                    class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>
            <button type="submit">Upload</button>
        </form>
        <img :src="imagePath" alt="Uploaded Image" v-if="imagePath" />
    </div>
</template>
  
<script setup>
import { ref } from 'vue';
import axios from 'axios';

axios.defaults.withCredentials = true;

const form = ref({
    photo: null,
});

const imagePath = ref('');
const photo = ref('');

async function uploadImage() {
    try {
        const response = await axios.post('http://localhost:8000/api/spaces/1', {
        _method: 'PUT',
            photo: form.value.photo,
        }, {
            headers: {
                'Content-Type': 'multipart/form-data'
            }
        });

        const data = response.data;
        console.log(data);
        imagePath.value = `/storage/${data.path}`;
    } catch (error) {
        console.error(error);
    }
};

async function handlePhotoChange(event) {
    const file = event.target.files[0];
    form.value.photo = file;
}
</script>
  