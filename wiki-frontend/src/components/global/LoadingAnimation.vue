<template>
    <div class="flex items-center justify-center h-[80vh]">
        <div class="w-fit h-16 animate-bounce">
            <span class="text-blue-500 font-bold text-lg">Wiki</span>
            <span class="text-red-500 font-bold text-lg">My</span>
            <span class="text-green-500 font-bold text-lg">Hd</span>
        </div>
        <div class="h-2 w-fit mt-2 relative">
            <div class="absolute top-0 h-full bg-red-500 animate-dot w-2 rounded-full"></div>
        </div>
        <!-- <div class="animate-spin rounded-full h-16 w-16 border-t-4 border-b-4 border-blue-500"></div> -->
    </div>
</template>

<script setup>

</script>


<style>
@keyframes dotAnimation {
    0% {
        left: 0;
        background-color: green;
    }


    25% {
        left: calc(100% - 3rem);
        background-color: red;
    }

    50% {
        left: calc(100% - 6rem);
        background-color: blue;
    }

    75% {
        left: calc(100% - 3rem);
        background-color: red;
    }

    100% {
        left: 0;
        background-color: green;
    }
}

.animate-dot {
    animation: dotAnimation 4s linear infinite;
}
</style>