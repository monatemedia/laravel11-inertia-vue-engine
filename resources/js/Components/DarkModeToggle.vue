<script setup>
import { ref, onMounted } from 'vue';
import { SunIcon } from '@heroicons/vue/24/outline'; // Import icons
import { MoonIcon } from '@heroicons/vue/24/solid'; // Import icons

// Reactive variable to track if dark mode is enabled
const isDarkMode = ref(false);

// Function to toggle dark mode
const toggleDarkMode = () => {
  const html = document.documentElement;

  if (isDarkMode.value) {
    html.classList.remove('dark');
    localStorage.setItem('theme', 'light');
  } else {
    html.classList.add('dark');
    localStorage.setItem('theme', 'dark');
  }

  // Toggle the dark mode state
  isDarkMode.value = !isDarkMode.value;
};

// Check for stored theme preference on page load
onMounted(() => {
  if (localStorage.getItem('theme') === 'dark') {
    document.documentElement.classList.add('dark');
    isDarkMode.value = true;
  } else {
    document.documentElement.classList.remove('dark');
    isDarkMode.value = false;
  }
});
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
