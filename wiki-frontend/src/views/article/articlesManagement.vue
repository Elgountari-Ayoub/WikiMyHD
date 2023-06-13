<template>
    <div id="AccountView">
        <RouterView />
        <DashboardLayout>
            <!-- table -->
            <!-- {{ articlesStore.articles }} -->
            <div v-if="userStore.isAdmin">
                <div class="relative  shadow-md sm:rounded-lg overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400 mb-20">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                            <tr>
                                <th scope="col" class="px-6 py-3">
                                    Titre
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Espace
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Manual
                                </th>
                                <th scope="col" class="px-6 py-3">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="article in articlesStore.articles" :key="article.id"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ article.title }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ article.space.titel }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ article.manual.title }}
                                </td>
                                <td class="px-6 py-4 text-left">
                                    <button @click="showArticle(article.id)"
                                        class="text-lg text-green-500 rounded-md hover:text-green-700 sm:text-sm md:text-base">
                                        <i class="ri-article-line"></i>
                                    </button>
                                    <button @click="toEditArticle(article.id)"
                                        class="text-lg text-blue-500 rounded-md hover:text-blue-700 sm:text-sm md:text-base">
                                        <i class="ri-pencil-line"></i>
                                    </button>
                                    <button @click="deleteArticle(manual.id)"
                                        class="text-lg text-red-500 rounded-md hover:text-red-700 sm:text-sm md:text-base">
                                        <i class="ri-delete-bin-6-line"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <LoadingAnimation v-else />
        </DashboardLayout>
    </div>
</template>

<script setup>
import { RouterView } from 'vue-router';
import DashboardLayout from '../../components/layouts/DashboardLayout.vue';
import { onMounted, ref } from 'vue';
import axios from 'axios';
import LoadingAnimation from '../../components/global/LoadingAnimation.vue';
import { useRouter } from 'vue-router';
import Dropdown from '../../components/global/Dropdown.vue';

import { computed, watchEffect } from 'vue';
import { useSpacesStore } from '../../stores/spaces-store';

import { useUserStore } from '../../stores/user-store';
import { useUsersStore } from '../../stores/users-store';
import Swal from 'sweetalert2';
import { useArticlesSotre } from '../../stores/articles-store';

axios.defaults.withCredentials = true;

const articlesStore = useArticlesSotre();
const userStore = useUserStore();
const router = useRouter();

onMounted(async () => {
    await articlesStore.getArticles();
    
});
const showModal = ref(false);


const userId = ref(null);
const status = ref(null);
const showAssignModel = async (user_id, _status) => {
    showModal.value = true;
    userId.value = user_id;
    status.value = _status;

}

const spaces = ref([]);
onMounted(async () => {
    await spacesStore.getSpaces();
    spaces.value = spacesStore.spaces;
})

const selectedSpaces = ref([]);
const selectedManuals = ref([]);
const selectedArticles = ref([]);

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
    approve(userId.value, status.value)
    userId.value = null;
    status.value = null;
    selectedSpaces.value = [];
    selectedManuals.value = [];
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



const approve = async (userId, status) => {
    await axios.post('/api/approve', {
        user_id: userId,
        status: status
    }).then(async (response) => {
        console.log(response);
        await usersStore.getUsers();
    }).catch(error => {
        console.log('ERROR IN APPROVING');
        console.log(error);

    })
}
const shareSpaces = async () => {
    await axios.post('/api/assignspace', {
        user_id: userId.value,
        spaces: selectedSpaces.value
    }).then(response => {
        console.log(response);
    }).catch(error => {
        console.log(error);
    })
}
const shareManuals = async () => {
    await axios.post('/api/assignmanual', {
        user_id: userId.value,
        manuals: selectedManuals.value
    }).then(response => {
        console.log(response);
    }).catch(error => {
        console.log(error);
    })
}
</script>

<style lang="scss" scoped></style>