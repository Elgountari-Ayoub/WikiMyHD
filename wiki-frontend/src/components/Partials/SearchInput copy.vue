<template>
    <div class="flex items-center justify-center flex-1 px-2 pt-0 mt-0 lg:ml-6 lg:justify-end">
    <div class="w-full max-w-lg lg:max-w-xs">
        <label for="search" class="sr-only">Search</label>
        <div class="relative">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                <SearchIcon class="w-5 h-5 text-gray-400" aria-hidden="true" />
            </div>
            <input id="search" name="search"
                class="block w-full pl-10 pr-3 leading-5 placeholder-gray-500 bg-white border border-gray-300 rounded-md focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                placeholder="Search" type="search" />
        </div>
    </div>
</div>
</template>

<script setup>

</script>

<style lang="scss" scoped>

</style>