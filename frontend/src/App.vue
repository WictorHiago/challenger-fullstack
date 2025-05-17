<script setup lang="ts">
import { computed } from 'vue'
import { useRouter, useRoute } from 'vue-router'

const router = useRouter()
const route = useRoute()

// Simulando um usuário logado para desenvolvimento
const isLoggedIn = computed(() => localStorage.getItem('token') !== null)
const userRole = computed(() => localStorage.getItem('userRole') || '')

const logout = () => {
  localStorage.removeItem('token')
  localStorage.removeItem('userRole')
  router.push('/login')
}

const isAuthPage = computed(() => {
  return route.path === '/login' || route.path === '/register'
})
</script>

<template>
  <div class="min-h-screen flex flex-col">
    <!-- Header/Navbar -->
    <header v-if="!isAuthPage" class="bg-white shadow">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            <div class="flex-shrink-0 flex items-center">
              <h1 class="text-2xl font-bold text-blue-600">StoreManager</h1>
            </div>
            <nav class="ml-6 flex space-x-4 items-center">
              <router-link to="/" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-100">
                Home
              </router-link>
              <router-link to="/products" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-100">
                Products
              </router-link>
              <router-link to="/categories" class="px-3 py-2 rounded-md text-sm font-medium hover:bg-gray-100">
                Categories
              </router-link>
            </nav>
          </div>
          <div class="flex items-center">
            <button v-if="isLoggedIn" @click="logout" class="btn btn-secondary">
              Logout
            </button>
            <template v-else>
              <router-link to="/login" class="btn btn-primary mr-2">
                Login
              </router-link>
              <router-link to="/register" class="btn btn-secondary">
                Register
              </router-link>
            </template>
          </div>
        </div>
      </div>
    </header>

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