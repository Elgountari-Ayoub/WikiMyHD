
<template>
  <RouterView />
  <div>
    <DashboardLayout>
      <RouterLink :to="{ name: 'spaces' }" class="hover:text-gray-900  text-base rounded">
        <svg aria-hidden="true"
          class="w-6 h-8 inline text-gray-500 transition duration-75 dark:text-gray-400 group-hover:text-gray-900 dark:group-hover:text-white transform transition-transform"
          fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7">
          </path>
        </svg>
      </RouterLink>

    <!-- <RouterLink :to="{ name: 'space', params: { id: `${spaceStore.id}` } }"
        class="hover:text-blue-500 text-base  rounded">
        {{ spaceStore.title }}
                  </RouterLink> -->
      <button @click="toSpace(spaceStore.id)" class="hover:text-blue-500 text-base  rounded">
        {{ spaceStore.title }}
      </button>

      <div>
        <!-- Add btn and search -->
        <div class="flex items-center mb-4 gap-4">
          <!-- Add manual : most have the space that will have the manual-->
          <button @click="openModal" type="submit"
            class="px-4 py-2 w-2/12 text-white text-sm bg-green-500 rounded-md hover:bg-green-600 ">
            Ajouter Manual
          </button>
          <!-- <SearchInput /> -->
          <!-- md:w-4/12 lg:w-6/12 sm:w-4/12   -->
          <form class="relative z-10 flex items-center w-8/12 m-auto" @submit.prevent="search">
            <div class="relative w-full m-auto">
              <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <svg aria-hidden="true" class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                  viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd"
                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                    clip-rule="evenodd"></path>
                </svg>
              </div>
              <input v-model="searchInput" name="search" type="text" id="simple-search"
                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-gray-500 focus:border-gray-500 block w-full pl-10 p-2.5  dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-gray-500 dark:focus:border-gray-500 "
                placeholder="Search">
            </div>
            <button type="submit"
              class="p-2.5 ml-2 text-sm font-medium text-white bg-blue-700 rounded-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
              </svg>
              <span class="sr-only">Search</span>
            </button>
          </form>
        </div>

        <LoadingAnimation v-if="manualsStore.manuals.length == 0" />
        <div v-else
          class="grid sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl-custom-grid-cols-4 xl:grid-cols-4 gap-4 mb-4">

          <div v-for="manual in manualsStore.manuals"
            class="flex flex-col shadow-md justify-between gap-2 rounded h-60 bg-gray-50 dark:bg-gray-800 p-4">
            <div class="border-b flex justify-end gap-2 ">{{ manual.users.length }}<i class="ri-group-line"></i></div>

            <!-- Manual logo [first letter] -->
          <!-- <RouterLink :to="{ name: 'manual', params: { id: `${manual.id}` } }"
              class="flex items-center justify-center w-16 h-16 rounded-full m-auto text-white"
              :style="{ backgroundColor: manual.color }">
              <span class="text-2xl ">{{ manual.title[0].toUpperCase() }}
              </span>
                            </RouterLink> -->
            <button @click="toManual(manual.space.id, manual.id)"
              class="flex items-center justify-center w-16 h-16 rounded-full m-auto text-white"
              :style="{ backgroundColor: manual.color }">
              <span class="text-2xl ">{{ manual.title[0].toUpperCase() }}
              </span>
            </button>

            <div class="flex justify-center items-center">
              <!-- Title -->
              <button @click="toManual(manual.space.id, manual.id)" class="font-bold hover:text-blue-500">
                {{ manual.title.slice(0,
                  100) }}
              </button>

              <!-- Btns -->
              <div class="ml-auto flex gap-4" v-if='getCreatorId(manual.users) == userStore.id || userStore.isAdmin'>
                <button @click="openEditManualModal(manual.id, manual.title, manual.description)"
                  class="text-lg text-blue-500 rounded-md hover:text-blue-700 sm:text-sm md:text-base">
                  <i class="ri-pencil-line"></i>
                </button>
                <button @click="deleteManual(manual.id)"
                  class="text-lg text-red-500 rounded-md hover:text-red-700 sm:text-sm md:text-base">
                  <i class="ri-delete-bin-6-line"></i>
                </button>
              </div>
            </div>
            <span class="mr-auto text-sm">By {{ getCreatorName(manual.users) }}</span>
          </div>
        </div>

        <!-- Modal  Add Manual form-->
        <div v-if="isModalOpen" class="fixed inset-0 z-50 flex items-center justify-center " @click.self="closeModal">
          <div class="p-6 bg-white rounded-md shadow-2xl w-96" ref="modal">
            <h1 class="mb-4 text-2xl font-semibold">Ajouter un manual</h1>
            <form @submit.prevent="addManual" class="space-y-4 ">
              <div>
                <label for="title" class="block mb-2 font-medium text-gray-700">Titre</label>
                <input v-model="form.title" type="text" id="title" name="title"
                  class="w-full px-4 py-2 border-gray-300 rounded-md" placeholder="Enter a title" required>
              </div>
              <div>
                <label for="description" class="block mb-2 font-medium text-gray-700">Description</label>
                <textarea v-model="form.description" id="description" name="description" rows="4"
                  class="w-full px-4 py-2 border-gray-300 rounded-md" placeholder="Enter a description"
                  required></textarea>
              </div>
              <div class="flex justify-end">
                <button type="submit"
                  class="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600">Submit</button>
              </div>
            </form>
          </div>
        </div>

        <!-- Modal  Edit Manual form-->
        <div v-if="isEditManualModalOpen" class="fixed inset-0 z-50 flex items-center justify-center "
          @click.self="closeEditManualModal">
          <div class="p-6 bg-white rounded-md shadow-2xl w-96" ref="modal">
            <h1 class="mb-4 text-2xl font-semibold">Modifier le manual</h1>
            <form @submit.prevent="editManual" class="space-y-4 ">
              <div>
                <label for="title" class="block mb-2 font-medium text-gray-700">Titre</label>
                <input v-model="form.title" type="text" id="title" name="title"
                  class="w-full px-4 py-2 border-gray-300 rounded-md" placeholder="Enter a title" required>
              </div>
              <div>
                <label for="description" class="block mb-2 font-medium text-gray-700">Description</label>
                <textarea v-model="form.description" id="description" name="description" rows="4"
                  class="w-full px-4 py-2 border-gray-300 rounded-md" placeholder="Enter a description"
                  required></textarea>
              </div>
              <div class="flex justify-end">
                <button type="submit"
                  class="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600">Editer</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </DashboardLayout>
  </div>
</template>


<script setup>
import DashboardLayout from '../../components/layouts/DashboardLayout.vue';
import SearchInput from '../../components/Partials/SearchInput.vue';
import Swal from 'sweetalert2';
import Dropdown from '../../components/global/Dropdown.vue';
import LoadingAnimation from '../../components/global/LoadingAnimation.vue'

import { ref, watchEffect, onMounted, watch, onBeforeUnmount } from 'vue';
import axios, { Axios } from 'axios';
import { RouterView, useRoute, useRouter } from 'vue-router';
// Store [pinia]
import { useUserStore } from '../../stores/user-store';

import { useSpacesStore } from '../../stores/spaces-store';
import { useSpaceStore } from '../../stores/space-store';

import { useManualsStore } from '../../stores/manuals-store';
import { useParamsStore } from '../../stores/params-store';


const userStore = useUserStore();
const manualsStore = useManualsStore();
const spaceStore = useSpaceStore();
const spacesStore = useSpacesStore();
const paramsStore = useParamsStore();

axios.defaults.withCredentials = true;

const route = useRoute();
const router = useRouter();

manualsStore.clearManuals();
spaceStore.clearSpace();

const getManuals = onMounted(async () => {
  if (paramsStore.getSpaceId()) {
    spaceStore.getSpace(paramsStore.getSpaceId());
    manualsStore.getManualsBySpace(paramsStore.getSpaceId());
    spacesStore.getSpaces();
  }
});

onBeforeUnmount(() => {
  paramsStore.clear();
})



const isModalOpen = ref(false);
const isEditManualModalOpen = ref(false);

const modalRef = ref(null);


const form = ref({
  id: null,
  title: null,
  description: null,
})

// MANAUL CRUD + SEARCH

// Add Manual
const addManual = async () => {
  try {
    const response = await axios.post('/api/manuals', {
      space_id: spaceStore.id,
      title: form.value.title,
      description: form.value.description
    });
    closeModal();
    // Reset form fields after successful submission
    form.value.is = '';
    form.value.title = '';
    form.value.description = '';
    getManuals();
  } catch (error) {
    // Handle the error here if needed
    Swal.fire({
      position: 'top-end',
      icon: 'warning',
      title: 'error d\'insertion',
      showConfirmButton: false,
      timer: 1500
    })
    console.error(error);
  }
};

// Edit Manual
const editManual = async () => {
  try {
    const response = await axios.post(`/api/manuals/${form.value.id}`, {
      _method: 'PUT',
      title: form.value.title,
      description: form.value.description
    });
    closeEditManualModal();
    // Reset form fields after successful submission
    form.value.is = '';
    form.value.title = '';
    form.value.description = '';

    // Swal.fire({
    //     position: 'top-end',
    //     icon: 'success',
    //     width: '25rem',
    //     title: 'le manuel mis à jour avec succès',
    //     showConfirmButton: false,
    //     timer: 1500,
    // })
    getManuals();
  } catch (error) {
    // Handle the error here if needed
    Swal.fire({
      position: 'top-end',
      icon: 'warning',
      title: 'échec de la mise à jour de le manuel',
      showConfirmButton: false,
      timer: 1500
    })
    console.error(error);
  }
}

// delete Manual
const deleteManual = async (manualId) => {
  // show a sweet alert for the confirmation
  try {
    const response = await axios.delete(`/api/manuals/${manualId}`);
    getManuals();
    // Swal.fire({
    //     position: 'top-end',
    //     icon: 'success',
    //     title: 'le manuel a été supprimé avec succès',
    //     showConfirmButton: false,
    //     timer: 1500

    // })

  } catch (err) {
    Swal.fire({
      position: 'top-end',
      icon: 'warning',
      title: 'Échec de la suppression, actualisez la page et réessayez',
      showConfirmButton: false,
      timer: 1500
    })
  }
}

// Search
const searchInput = ref(null)
const search = async () => {
  try {
    if (!searchInput.value) {
      if (manualsStore.manuals.length === 0) {
        manualsStore.getManuals();
      }
      return;
    }
    const response = await axios.get(`/api/manuals/search/${searchInput.value}`);
    if (response.data.length > 0) {
      manualsStore.manuals = response.data
      manualsStore.manuals.forEach(element => {
        element.color = "#" + Math.floor(Math.random() * 16777215).toString(16);
      })
      return;
    }
    manualsStore.manuals = [];

  } catch (error) {
    // Handle the error here if needed
    console.error(error);
  }
};

// HELPERS-------------------------
// Edit Manual Modal
const toSpace = (spaceId) => {
  console.log(spaceId);
  paramsStore.setSpaceId(spaceId);
  router.push({ name: 'space' });
}
function toManual(spaceId, manualId) {
  console.log(spaceId, manualId);
  paramsStore.setSpaceId(spaceId);
  paramsStore.setManualId(manualId);
  router.push({ name: 'manual' })
}





const openEditManualModal = (manualId, manualTitle, manualDescription) => {
  form.value.id = manualId;
  form.value.title = manualTitle;
  form.value.description = manualDescription;
  isEditManualModalOpen.value = true;
};
const closeEditManualModal = () => {
  isEditManualModalOpen.value = false;
};
// Close modal when clicking outside
watchEffect(() => {
  const handleClickOutside = (event) => {
    const modalElement = modalRef.value;

    if (modalElement && !modalElement.contains(event.target)) {
      closeModal();
    }
  };

  document.addEventListener('click', handleClickOutside);

  return () => {
    document.removeEventListener('click', handleClickOutside);
  };
});

watch(() => spaceStore.id, (newValue, oldValue) => {
  getManuals();
});


const getCreatorId = (users) => {
  let creatorId = -1;
  users.forEach(user => {
    if (user.pivot.is_creator == 1) {
      creatorId = user.id;
    }
  });
  return creatorId;
}

const getCreatorName = (users) => {
  let creatorName = -1;
  users.forEach(user => {
    if (user.pivot.is_creator == 1) {
      creatorName = user.name;
    }
  });
  return creatorName;
}

// Add manual model
const openModal = () => {
  form.value.title = '';
  form.value.description = '';
  isModalOpen.value = true;
};

const closeModal = () => {
  isModalOpen.value = false;
  form.value.title = '';
  form.value.description = '';
};


const getImageUrl = (photo) => {
  const baseUrl = "http://localhost:8000/storage/";
  return baseUrl + photo; // Concatenating the base URL and the photo variable
}

</script>

<style></style>