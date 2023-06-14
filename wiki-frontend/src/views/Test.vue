<template>
    <div>
        {{ selectedUsers }}

        <div
            class="fixed z-10 inset-0 flex items-center justify-center overflow-x-hidden overflow-y-auto outline-none focus:outline-none">
            <div class="relative mx-auto max-w-lg bg-white rounded-lg shadow-lg">
                <div class="flex flex-col items-start justify-between p-6 space-y-4 w-96">
                    <div class="text-lg font-bold text-gray-900 self-center">Users</div>
                    <div class="w-full">
                        <label for="spaces" class="block text-sm font-medium text-gray-700 mb-1"></label>
                        <select multiple v-model="selectedUsers" id="spaces" name="spaces[]"
                            class="w-full px-3 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-md">
                            <option v-for="user in usersStore.users" :value="user.id" :key="user.id">{{ user.name }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { useUsersStore } from '../stores/users-store';

const selectedUsers = ref([]);
const usersStore = useUsersStore();
onMounted(() => {
    usersStore.getUsers();
})

</script>

<style lang="scss" scoped></style>