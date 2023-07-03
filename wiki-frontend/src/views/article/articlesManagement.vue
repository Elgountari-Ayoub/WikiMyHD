<template>
    <div id="AccountView">
        <RouterView />
        <DashboardLayout>
            <!-- table -->
            <div v-if="userStore.isAdmin">
                <div class="relative shadow-md sm:rounded-lg overflow-x-auto min-h-[80vh]">
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
                                <th scope="col" class="px-6 py-3 ">
                                    Versions
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="article in articlesStore.articles" :key="article.id"
                                class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white"
                                    :title="article.title">
                                        {{ article.title.length > 30 ? article.title.slice(0, 30) + '...' : article.title }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ article.space.title }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ article.manual.title }}
                                </td>
                                <td class="px-6 py-4">
                                    <select v-model="selectedVersion" @change="toArticle(article.id)"
                                        class="outline-0 rounded-full">
                                        <option v-for="version in article.versions" :key="version.id" :value="version.id">
                                            {{ version.version_number }}
                                        </option>
                                    </select>

                                </td>
                                <td class="px-6 py-4 grid grid-cols-3 gap-1">
                                    <button @click="toArticle(article.id)"
                                        class="text-lg text-green-500 rounded-md hover:text-green-700 sm:text-sm md:text-base">
                                        <i class="ri-article-line"></i>
                                    </button>
                                    <button @click="toEditArticle(article.id)"
                                        class="text-lg text-blue-500 rounded-md hover:text-blue-700 sm:text-sm md:text-base">
                                        <i class="ri-pencil-line"></i>
                                    </button>
                                    <button @click="deleteArticle(article.id)"
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
import { useParamsStore } from '../../stores/params-store';

axios.defaults.withCredentials = true;

const paramsStore = useParamsStore();
const articlesStore = useArticlesSotre();
const userStore = useUserStore();
const router = useRouter();

articlesStore.clearArticles();

const getArticles = onMounted(async () => {
    await articlesStore.getArticles();
});

const selectedVersion = ref(null);
const toArticle = (articleId) => {
    paramsStore.articleId = articleId;
    paramsStore.articleVersion = selectedVersion.value;
    selectedVersion.value = null
    window.open('/article', '_blank');
}

const toEditArticle = (articleId) => {
    paramsStore.articleId = articleId
    router.push({ name: 'editArticle' })

};
// delete Article
const deleteArticle = async (articleId) => {
    Swal.fire({
        title: "Êtes-vous sûr(e) ?",
        text: "Une fois supprimé, cet élément ne pourra pas être récupéré !",
        icon: "warning",
        showDenyButton: true,
        confirmButtonText: 'Confirmer',
        denyButtonText: `Annuler`,
    }).then(async (result) => {
        if (result.isConfirmed) {
            // show a sweet alert for the confirmation
            await axios.delete(`/api/articles/${articleId}`).then(response => {
                getArticles();
            }).catch(error => {
                Swal.fire({
                    position: 'top-end',
                    icon: 'warning',
                    title: 'Échec de la suppression, actualisez la page et réessayez',
                    showConfirmButton: false,
                    timer: 1500
                })
            }
            )
        }
    })
}


</script>

<style lang="scss" scoped></style>