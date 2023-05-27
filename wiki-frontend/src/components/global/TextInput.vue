<template>
    <div>


        <label :class="[labelColor ? 'text-gray-700' : 'text-gray-100']">
            {{ label }}
        </label>
        <input :placeholder="placeholder"
            class="w-full px-4 py-2 mt-1 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            :type="inputType" v-model="inputComputed">
        <span v-if="error" class="text-red-500">
            {{ error }}
        </span>
    </div>
</template>

<script setup>
import { toRefs, computed } from 'vue'

const emit = defineEmits(['update:input'])

const props = defineProps({
    label: String,
    labelColor: { type: Boolean, default: true },
    input: String,
    placeholder: { type: String, default: '' },
    inputType: String,
    error: {type: String, default: ''}
})

const { label, labelColor, input, placeholder, error } = toRefs(props)

const inputComputed = computed({
    get: () => input.value,
    set: (val) => emit('update:input', val) 
})
</script>