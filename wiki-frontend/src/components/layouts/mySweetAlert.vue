<template>
    <div>
        <!-- SweetAlert Modal -->
        <div v-if="isOpen" class="fixed inset-0 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg shadow-lg p-6" :class="positionClass" :style="{ width: width, height: height }">
                <h3 class="text-xl font-semibold mb-4" :style="{ fontSize: titleFontSize }">{{ title }}</h3>
                <div class="flex items-start">
                    <div v-if="icon" class="mr-4">
                        <i :class="icon"></i>
                    </div>
                    <div class="text-base">{{ text }}</div>
                </div>
                <div class="mt-6 flex justify-end">
                    <button class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 mr-2"
                        @click="handleConfirm">{{ confirmationButtonText }}</button>
                    <button class="px-4 py-2 bg-gray-300 text-gray-700 rounded hover:bg-gray-400" @click="handleCancel">{{
                        cancelButtonText }}</button>
                </div>
            </div>
        </div>
        <!-- SweetAlert Overlay -->
        <div v-if="isOpen" class="fixed inset-0 bg-black opacity-50"></div>
    </div>
</template>
  
<script>
import { ref, onMounted, onUnmounted } from 'vue';

export default {
    props: {
        title: String,
        position: {
            type: String,
            default: 'center',
            validator: (value) => ['top', 'center', 'bottom'].includes(value),
        },
        titleFontSize: {
            type: String,
            default: '18px',
        },
        icon: String,
        text: String,
        width: {
            type: String,
            default: '400px',
        },
        height: {
            type: String,
            default: 'auto',
        },
        confirmationButtonText: {
            type: String,
            default: 'OK',
        },
        cancelButtonText: {
            type: String,
            default: 'Cancel',
        },
    },
    emits: ['confirm', 'cancel'],
    setup(props, { emit }) {
        const isOpen = ref(false);
        const positionClass = ref('');

        const handleConfirm = () => {
            emit('confirm');
            isOpen.value = false;
        };

        const handleCancel = () => {
            emit('cancel');
            isOpen.value = false;
        };

        const getPositionClass = () => {
            switch (props.position) {
                case 'top':
                    return 'items-start';
                case 'bottom':
                    return 'items-end';
                default:
                    return '';
            }
        };

        onMounted(() => {
            isOpen.value = true;
            positionClass.value = getPositionClass();
        });

        onUnmounted(() => {
            isOpen.value = false;
        });

        return {
            isOpen,
            positionClass,
            handleConfirm,
            handleCancel,
        };
    },
};
</script>
  