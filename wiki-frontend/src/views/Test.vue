<template>
    <div>
        {{ selectedUsers }}
        <button class="p-2 mt-2 bg-green-600 hover:bg-green-700 text-white rounded" @click="openSharModel()">Soumettre</button>

        <div v-if="isSharModelOpen" class="fixed z-10 inset-0 flex items-center justify-center overflow-x-hidden overflow-y-auto outline-none focus:outline-none">
            <div class="relative mx-auto max-w-lg bg-white rounded-lg shadow-lg">
                <div class="flex flex-col items-start justify-between p-6 space-y-4 w-96">
                    <div class="text-lg font-bold text-gray-900 self-center">Users</div>
                    <div class="w-full">
                        <label for="spaces" class="block text-sm font-medium text-gray-700 mb-1"></label>
                        <input type="text" v-model="searchQuery" placeholder="Search users"
                            class="w-full px-3 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-md mb-2" />
                        <select multiple v-model="selectedUsers" id="spaces" name="spaces[]"
                            class="w-full px-3 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-md">
                            <option v-for="user in filteredUsers" :value="user.id" :key="user.id">{{ user.name }}
                            </option>
                        </select>
                        <div class="flex justify-between">
                            <button class="p-2 mt-2 bg-blue-600 hover:bg-blue-700 text-white rounded" @click="shareWithUsers()">Soumettre</button>
                            <button class="p-2 mt-2 bg-red-600 hover:bg-red-700 text-white rounded" @click="closeSharModel()">Fermer</button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref, computed } from 'vue';
import { useUsersStore } from '../stores/users-store';
import { useManualsStore } from '../stores/manuals-store';

const selectedUsers = ref([]);
const searchQuery = ref('');
const usersStore = useUsersStore();
const isSharModelOpen = ref(false);

onMounted(() => {
    usersStore.getUsers();
});

const filteredUsers = computed(() => {
    return usersStore.users.filter(user => {
        return user.name.toLowerCase().includes(searchQuery.value.toLowerCase());
    });
});

function openSharModel() {
    isSharModelOpen.value = true;
}
function closeSharModel() {
    isSharModelOpen.value    = false;
}

</script>

<style lang="scss" scoped></style>
