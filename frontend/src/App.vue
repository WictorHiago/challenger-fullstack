<script setup lang="ts">
import { computed } from 'vue'
import { useRoute } from 'vue-router'
import Navbar from './components/Navbar.vue'

const route = useRoute()

// Verifica se estamos em uma página de autenticação
const isAuthPage = computed(() => {
  return route.path === '/login' || route.path === '/register'
})
</script>

<template>
  <div class="min-h-screen flex flex-col">
    <!-- Navbar component -->
    <Navbar v-if="!isAuthPage" />

    <!-- Main Content -->
    <main class="flex-grow">
      <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <router-view />
      </div>
    </main>

    <!-- Footer -->
    <footer v-if="!isAuthPage" class="bg-white shadow">
      <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
        <p class="text-center text-sm text-gray-500">
          &copy; {{ new Date().getFullYear() }} StoreManager. All rights reserved.
        </p>
      </div>
    </footer>
  </div>
</template>