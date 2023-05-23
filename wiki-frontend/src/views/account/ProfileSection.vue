<template>
  <div class="container mx-auto p-4" v-if="user">
    <p>new name => {{newName}}</p>
    <div class="flex justify-center items-center space-x-4">
      <div class="flex">
        <label for="name">Name:</label> 
        <div v-if="nameEditing">
          <input type="text" v-model="newName" >
          <button @click="updateUsername" class="text-green-500 hover:underline cursor-pointer">Save</button>
          <button @click="nameEditing = !nameEditing" class="text-red-500 hover:underline cursor-pointer">Cancel</button>
        </div>
        <div v-else class="flex gap-2 justify-between">
          <p>{{ user.name }}</p>
          <button @click="nameEditing = !nameEditing" class="bg-blue-500 hover:bg-blue-700 text-white px-1 cursor-pointer mx-2 px-2 py-1 rounded-md">Edit</button>
        </div>
      </div>

      <!-- <div>
        <h2 class="text-2xl font-semibold" v-if="editingName">
          <input v-model="newName" type="text" class="border border-gray-400 px-2 py-1 rounded">
          <button @click="updateName" class="text-green-500 hover:underline cursor-pointer">Save</button>
          <button @click="cancelName" class="text-red-500 hover:underline cursor-pointer">Cancel</button>
        </h2>
        <h2 class="text-2xl font-semibold" v-else>{{ user.name }}</h2>
        <p v-if="editingUsername">
          <input v-model="newUsername" type="text" class="border border-gray-400 px-2 py-1 rounded">
          <button @click="updateUsername" class="text-green-500 hover:underline cursor-pointer">Save</button>
          <button @click="cancelUsername" class="text-red-500 hover:underline cursor-pointer">Cancel</button>
        </p>
        <p v-else>{{ user.username }}</p>
      </div> -->
    </div>
  </div>
  <div v-else class="container mx-auto p-4">
    Loading...
  </div>
</template>

<script setup>
import { ref, onMounted, camelize } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import TextInput from '../../components/global/TextInput.vue';

axios.defaults.withCredentials = true;

const user = ref(null);
const router = useRouter();


let nameEditing = ref(false);

const newName = ref('');

const fetchUser = async () => {
  try {
    const { data } = await axios.get('http://localhost:8000/api/user');
    user.value = data;
  } catch (error) {
    console.error(error);
  }
};

onMounted(fetchUser);

const updateName = async () => {
  try {
    const response = await axios.put('http://localhost:8000/api/user', {
      name: newName.value
    });

    console.log('Name updated successfully:', response.data);

    user.value.name = response.data.name;
    nameEditing.value = false;
  } catch (error) {
    console.error('Failed to update name:', error.response.data);
  }
};

const cancelName = () => {
  nameEditing.value = false;
};
</script>

<style>
/* Add your custom styles here if needed */
</style>
