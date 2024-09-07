<script setup>
import { ref } from 'vue';
import { SunIcon } from '@heroicons/vue/24/outline'; // Import icons
import { MoonIcon } from '@heroicons/vue/24/solid'; // Import icons

const isDarkMode = ref(document.documentElement.classList.contains('dark'));

const toggleDarkMode = () => {
  if (isDarkMode.value) {
    document.documentElement.classList.remove('dark');
    localStorage.setItem('color-theme', 'light');
  } else {
    document.documentElement.classList.add('dark');
    localStorage.setItem('color-theme', 'dark');
  }
  isDarkMode.value = !isDarkMode.value;
};
</script>

<template>
  <button
    @click="toggleDarkMode"
    class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2.5"
  >
    <MoonIcon v-if="!isDarkMode" class="w-5 h-5" />
    <SunIcon v-if="isDarkMode" class="w-5 h-5" />
  </button>
</template>
