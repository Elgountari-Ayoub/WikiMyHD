<template>
  <DashboardLayout>
    <div class="container mx-auto p-4" v-if="user">
      <div class="flex flex-col gap-4 w-52 items-center">
        <img v-if="user.photo" class="w-52 h-52 rounded-full mr-auto" :src="getImageUrl(userStore.photo)" alt="">
        <svg v-else fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" class="w-52 h-52 rounded-full mr-auto
                flex-shrink-0  bg-white  text-gray-500 transition d
                uration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white"
          xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M17.982 18.725A7.488 7.488 0 0012 15.75a7.488 7.488 0 00-5.982 2.975m11.963 0a9 9 0 10-11.963 0m11.963 0A8.966 8.966 0 0112 21a8.966 8.966 0 01-5.982-2.275M15 9.75a3 3 0 11-6 0 3 3 0 016 0z">
          </path>
        </svg>
        <span class="bg-green-400 py-2 px-4 rounded text-white ">{{ user.role }}</span>
      </div>
      <div class="px-8 mt-5 border-t border-gray-200">

        <dl class="divide-y divide-gray-200">
          <!-- ---------------- NAME-->
          <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
            <dt class="text-sm font-medium text-gray-500">Nom</dt>
            <dd class="flex justify-between mt-1 text-gray-900 text-tweensm sm:mt-0 sm:col-span-2">
              <form v-if="nameEditing" @submit.prevent="updateUser">
                <input type="text" class="flex-grow" v-model="newName">
                <!-- <InputError :message="form.errs.name" class="mt-2" /> -->
                <div class="space-x-2">
                  <PrimaryButton class="mt-4">Save</PrimaryButton>
                  <button class="mt-4" @click="nameEditing = false">Annuler</button>
                </div>
              </form>
              <span v-else class="flex-grow">{{ user.name }}</span>


              <span class="flex-shrink-0 ml-4">
                <button type="button" @click="nameEditing = true"
                  class="font-medium text-indigo-600 bg-white rounded-md hover:text-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Mise à jour</button>
              </span>

            </dd>
          </div>
          <!-- ---------------- EMAIL-->
          <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
            <dt class="text-sm font-medium text-gray-500">Email</dt>
            <dd class="flex justify-between mt-1 text-gray-900 text-tweensm sm:mt-0 sm:col-span-2">
              <form v-if="emailEditing" @submit.prevent="updateUser">
                <input type="email" class="flex-grow" v-model="newEmail">
                <!-- <InputError :message="form.errs.name" class="mt-2" /> -->
                <div class="space-x-2">
                  <PrimaryButton class="mt-4">Save</PrimaryButton>
                  <button class="mt-4" @click="emailEditing = false">Annuler</button>
                </div>
              </form>
              <span v-else class="flex-grow">{{ user.email }}</span>


              <span class="flex-shrink-0 ml-4">
                <button type="button" @click="emailEditing = true"
                  class="font-medium text-indigo-600 bg-white rounded-md hover:text-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Mise à jour</button>
              </span>
            </dd>
          </div>
          <!-- ---------------- PASSWORD-->
          <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
            <dt class="text-sm font-medium text-gray-500">Mot de passe</dt>
            <dd class="flex justify-between mt-1 text-gray-900 text-tweensm sm:mt-0 sm:col-span-2">
              <form v-if="passwordEditing" @submit.prevent="updateUser">
                <input type="password" class="flex-grow" v-model="newPassword">
                <!-- <InputError :message="form.errs.name" class="mt-2" /> -->
                <div class="space-x-2">
                  <PrimaryButton class="mt-4">Save</PrimaryButton>
                  <button class="mt-4" @click="passwordEditing = false">Annuler</button>
                </div>
              </form>
              <span v-else class="flex-grow overflow-hidden">•••••••••••••</span>

              <span class="flex-shrink-0 ml-4">
                <button type="button" @click="passwordEditing = true, newPassword = ''"
                  class="font-medium text-indigo-600 bg-white rounded-md hover:text-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Mise à jour</button>
              </span>
            </dd>
          </div>

          <!-- ---------------- POST-->
          <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
            <dt class="text-sm font-medium text-gray-500">Post</dt>
            <dd class="flex justify-between mt-1 text-gray-900 text-tweensm sm:mt-0 sm:col-span-2">
              <form v-if="postEditing" @submit.prevent="updateUser">
                <input type="text" class="flex-grow" v-model="newPost">
                <!-- <InputError :message="form.errs.name" class="mt-2" /> -->
                <div class="space-x-2">
                  <PrimaryButton class="mt-4">Save</PrimaryButton>
                  <button class="mt-4" @click="postEditing = false">Annuler</button>
                </div>
              </form>
              <span v-else class="flex-grow">{{ user.post ?? 'Not assigned yet' }}</span>


              <span class="flex-shrink-0 ml-4" v-if="isAdmin">
                <button type="button" @click="postEditing = true"
                  class="font-medium text-indigo-600 bg-white rounded-md hover:text-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Mise à jour</button>
              </span>

            </dd>
          </div>
          <!-- ---------------- ROOLE-->
          <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
            <dt class="text-sm font-medium text-gray-500">Role</dt>
            <dd class="flex justify-between mt-1 text-gray-900 text-tweensm sm:mt-0 sm:col-span-2">
              <form v-if="roleEditing" @submit.prevent="updateUser">
                <input type="text" class="flex-grow" v-model="newRole">
                <!-- <InputError :message="form.errs.name" class="mt-2" /> -->
                <div class="space-x-2">
                  <PrimaryButton class="mt-4">Save</PrimaryButton>
                  <button class="mt-4" @click="roleEditing = false">Annuler</button>
                </div>
              </form>
              <span v-else class="flex-grow">{{ user.role }}</span>

              <span class="flex-shrink-0 ml-4" v-if="isAdmin">
                <button type="button" @click="roleEditing = true"
                  class="font-medium text-indigo-600 bg-white rounded-md hover:text-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Mise à jour</button>
              </span>

            </dd>
          </div>

          <!-- ---------------- PHOTO-->

          <div class="py-4 sm:py-5 sm:grid items-center sm:grid-cols-3 sm:gap-4">
            <dt class="text-sm font-medium text-gray-500">Photo</dt>
            <dd class="flex justify-between  mt-1 text-gray-900 text-tweensm sm:mt-0 sm:col-span-2">
              <form v-if="photoEditing" @submit.prevent="updateUser" enctype="multipart/form-data">
                <input type="file" id="photo" @change="handlePhotoChange" ref="photoInput"
                  class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                  aria-describedby="file_input_help">
                <!-- <p class="mt-1 text-sm text-gray-500 dark:text-gray-300" id="file_input_help">SVG, PNG, JPG or GIF (MAX.
                  800x400px).</p> -->

                <div class="space-x-2">
                  <PrimaryButton class="mt-4">Save</PrimaryButton>
                  <button class="mt-4" @click="photoEditing = false">Annuler</button>
                </div>
              </form>
              <span v-else class="flex-grow flex items-center justify-between ">
                <img class="h-16 rounded-lg" :src="getImageUrl(userStore.photo)" alt="">
                <span class="flex-shrink-0 ml-4">
                  <button type="button" @click="photoEditing = true"
                    class="font-medium text-indigo-600 bg-white rounded-md hover:text-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Mise à jour</button>
                </span>
              </span>

            </dd>
          </div>

        </dl>
      </div>

    </div>

    <LoadingAnimation v-else />
  </DashboardLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { useRouter } from 'vue-router';
import TextInput from '../../components/global/TextInput.vue';
import PrimaryButton from '../../components/global/PrimaryButton.vue';
import { computed } from '@vue/reactivity';
import DashboardLayout from '../../components/layouts/DashboardLayout.vue';
import { useUserStore } from '../../stores/user-store'

import checkLoggedIn from '../../auth.js';


axios.defaults.withCredentials = true;

// const logg = ;
let logg = axios.defaults.baseURL;

const userStore = useUserStore();

const user = ref(null);
const router = useRouter();

let isAdmin = ref(false);

const newName = ref('');
const newEmail = ref('');
const newPassword = ref('');
const newPhoto = ref('');
const newRole = ref('');
const newPost = ref('');

let nameEditing = ref(false);
let emailEditing = ref(false);
let passwordEditing = ref(false);
let photoEditing = ref(false);
let roleEditing = ref(false);
let postEditing = ref(false);


onMounted(() => {
    if (!userStore.getUser()) {
        router.push({ name: 'login' });
        Swal.fire({
            icon: 'warning',
            // title: 'connexion réussie',
            text: 'Vous n\'êtes pas autorisé à accéder à cette page.',
            showConfirmButton: false,
            timer: 3000
        })
    }

  user.value = userStore;

  newName.value = user.value.name || null
  newEmail.value = user.value.email || null
  newPassword.value = user.value.password || null
  newPhoto.value = user.value.photo || null
  newRole.value = user.value.role || null
  newPost.value = user.value.post || null
  isAdmin = user.value.isAdmin;

})


const updateUser = async () => {
  try {
    const response = await axios.post('/api/update',
      {
        _method: 'PUT',
        name: newName.value,
        email: newEmail.value,
        password: newPassword.value,
        post: newPost.value,
        role: newRole.value,
        photo: newPhoto.value,
      }, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    }
    ).then(response => {
      console.log(response);
    });

    userStore.getUser();

    nameEditing.value = false;
    emailEditing.value = false;
    passwordEditing.value = false;
    photoEditing.value = false;
    roleEditing.value = false;
    postEditing.value = false;
  } catch (error) {
    // console.error('Failed to update user:', error.response.data.message);
    console.error('Failed to update user:', error);
  }
};


async function handlePhotoChange(event) {
  const file = event.target.files[0];
  newPhoto.value = file;
}


function getImageUrl(photo) {
  if (photo) {
    const baseUrl = axios.defaults.baseURL + "/storage/";

    return baseUrl + photo; 
  }
  return ' ';
}

</script>

<style>
/* Add your custom styles here if needed */
</style>
