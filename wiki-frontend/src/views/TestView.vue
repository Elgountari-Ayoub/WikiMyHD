<template>
    <div>
      <button @click="showModal = true" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        Open Modal
      </button>
  
      <!-- Modal -->
      <div v-if="showModal"
        class="fixed z-10 inset-0 flex items-center justify-center overflow-x-hidden overflow-y-auto outline-none focus:outline-none">
        <div class="relative mx-auto max-w-lg bg-white rounded-lg shadow-lg">
          <div class="flex flex-col items-start justify-between p-6 space-y-4">
            <div class="text-lg font-bold text-gray-900">Modal Title</div>
  
            <!-- Spaces Multi-Select -->
            <div class="w-full">
              <label for="spaces" class="block text-sm font-medium text-gray-700 mb-1">Spaces</label>
              <select multiple v-model="selectedSpaces" id="spaces" name="spaces[]"
                class="w-full px-3 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-md">
                <option v-for="space in spaces" :value="space.id" :key="space.id">{{ space.title }}</option>
              </select>
            </div>
  
            <!-- Manuals Dropdown -->
            <div class="w-full">
              <label for="manual" class="block text-sm font-medium text-gray-700 mb-1">Manuals</label>
              <select multiple v-model="selectedManuals" id="manual" name="manual[]"
                class="w-full px-3 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-md">
                <option v-for="manual in filteredManuals" :value="manual.id" :key="manual.id">{{ manual.title }}</option>
              </select>
            </div>
  
            <div class="flex justify-end space-x-4">
              <button @click="showModal = false" type="button"
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                Annuler
              </button>
              <button @click="handleSubmit" type="button"
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Soumettre
              </button>
            </div>
          </div>
        </div>
      </div>
  
      {{ selectedSpaces }}
      {{ selectedManuals }}
  
      <!-- Selected Spaces and Manuals -->
      <div v-if="selectedSpaces.length > 0 || selectedManuals.length > 0" class="mt-4">
        <div class="text-lg font-bold">Selected Spaces:</div>
        <ul class="list-disc ml-4">
          <li v-for="spaceId in selectedSpaces" :key="spaceId">{{ spaceId }}</li>
        </ul>
        <div class="text-lg font-bold mt-4">Selected Manuals:</div>
        <ul class="list-disc ml-4">
          <li v-for="manual in selectedManuals" :key="manual">{{ manual }}</li>
        </ul>
      </div>
    </div>
  </template>
    
  <script setup>
  import { ref, computed, watchEffect, onMounted } from 'vue';
  import { useSpacesStore } from '../stores/spaces-store';
  import axios from 'axios';
  
  
  const spacesStore = useSpacesStore();
  
  const spaces = ref([]);
  onMounted(async () => {
     await spacesStore.getSpaces();
     spaces.value = spacesStore.spaces;
  })
  const showModal = ref(false);
  // const spaces = ref([
  //   {
  //     id: 1,
  //     title: 'Space 1',
  //     manuals: [
  //       { id: 1, title: 'Manual 1' },
  //       { id: 2, title: 'Manual 2' },
  //     ],
  //   },
  //   {
  //     id: 2,
  //     title: 'Space 2',
  //     manuals: [
  //       { id: 3, title: 'Manual 3' },
  //       { id: 4, title: 'Manual 4' },
  //     ],
  //   },
  // ]);
  
  const selectedSpaces = ref([]);
  const selectedManuals = ref([]);
  
  const filteredManuals = computed(() => {
    const filtered = [];
    for (const spaceId of selectedSpaces.value) {
      const space = spaces.value.find((space) => space.id === spaceId);
      if (space) {
        filtered.push(...space.manuals);
      }
    }
    return filtered;
  });
  
  const handleSubmit = () => {
    showModal.value = false;
    shareSpaces();
    shareManuals();
  };
  
  const updateManuals = () => {
    const selectedSpaceIds = selectedSpaces.value;
    selectedManuals.value = selectedManuals.value.filter((manualId) => {
      const manualSpace = spaces.value.find((space) => space.manuals.some((manual) => manual.id === manualId));
      return manualSpace && selectedSpaceIds.includes(manualSpace.id);
    });
  };
  
  watchEffect(() => {
    // Update selected manuals when spaces selection changes
    updateManuals();
  });
  
  
  
  const shareSpaces = async () => {
    await axios.post('/api/assignspace', {
      user_id: 3,
      spaces: selectedSpaces.value
    })
  }
  const shareManuals= async () => {
    await axios.post('/api/assignmanual', {
      user_id: 3,
      manuals: selectedManuals.value
    })
  }
  
  // ---------------------
  
  
  </script>
    
  <style scoped>
  /* Add your custom styles here if needed */
  </style>