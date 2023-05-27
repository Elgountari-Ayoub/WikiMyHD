<template>
    <router-link 
        :class="[color ? 'hover:bg-' + color + '-500 hover:bg-' + color + '-600' : '']"
        class="px-4 py-2 text-white rounded-md ">

        :to="url"
    >
        {{ btnText }}
    </router-link>              
</template>

<script setup>
    import { toRefs } from 'vue'

    const props = defineProps({
        btnText: String, 
        url: { type: String, default: '' }, 
        color: { type: String, default: 'blue' }, 
    })
    const { btnText, url, color } = toRefs(props)

</script>