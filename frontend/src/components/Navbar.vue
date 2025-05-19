<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import { useToast } from 'vue-toastification';
import authService from '../services/authService';

const router = useRouter();
const toast = useToast();

const isAuthenticated = ref(false);
const user = ref<any>(null);
const isAdmin = ref(false);

// Estado do dropdown de usuário
const dropdownOpen = ref(false);

// Atualiza o estado de autenticação
const updateAuthState = () => {
  isAuthenticated.value = authService.isAuthenticated();
  user.value = authService.getCurrentUser();
  isAdmin.value = authService.isAdmin();
};

// Função para alternar o dropdown
const toggleDropdown = () => {
  dropdownOpen.value = !dropdownOpen.value;
};

// Função para fechar o dropdown quando clicar fora dele
const closeDropdown = (event: MouseEvent) => {
  const target = event.target as HTMLElement;
  if (!target.closest('.user-dropdown')) {
    dropdownOpen.value = false;
  }
};

// Função para fazer logout
const logout = async () => {
  try {
    await authService.logout();
    toast.success('Logout realizado com sucesso!');
    updateAuthState();
    router.push('/login');
  } catch (error) {
    console.error('Erro ao fazer logout:', error);
    toast.error('Erro ao fazer logout');
  }
};

// Verifica o estado de autenticação quando o componente é montado
onMounted(() => {
  updateAuthState();
  // Adiciona evento de clique global para fechar o dropdown
  document.addEventListener('click', closeDropdown);
});

// Remove o evento de clique quando o componente é desmontado
onUnmounted(() => {
  document.removeEventListener('click', closeDropdown);
});

// Computa o nome de exibição do usuário
const displayName = computed(() => {
  return user.value?.name || 'Usuário';
});
</script>

<template>
  <nav class="bg-blue-600 text-white p-4 shadow-md">
    <div class="container mx-auto flex justify-between items-center">
      <div class="flex items-center">
        <router-link to="/" class="text-xl font-bold">StoreManager</router-link>
      </div>
      
      <div class="flex items-center space-x-4">
        <!-- Links visíveis para todos os usuários autenticados -->
        <template v-if="isAuthenticated">
          <router-link 
            to="/products" 
            class="px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out hover:bg-blue-700 hover:text-white"
            active-class="bg-blue-700 text-white"
          >
            <div class="flex items-center">
              <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
              </svg>
              Produtos
            </div>
          </router-link>
          
          <router-link 
            to="/categories" 
            class="px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out hover:bg-blue-700 hover:text-white"
            active-class="bg-blue-700 text-white"
          >
            <div class="flex items-center">
              <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
              </svg>
              Categorias
            </div>
          </router-link>
          
          <!-- Links visíveis apenas para administradores -->
          <template v-if="isAdmin">
            <router-link 
              to="/users" 
              class="px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out hover:bg-blue-700 hover:text-white"
              active-class="bg-blue-700 text-white"
            >
              <div class="flex items-center">
                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                Usuários
              </div>
            </router-link>
          </template>
          
          <!-- Dropdown de usuário - Novo sistema clicável -->
          <div class="relative user-dropdown" @click.stop>
            <button 
              @click="toggleDropdown" 
              class="flex items-center hover:text-blue-200 px-3 py-2 rounded-md transition duration-150 ease-in-out"
              :class="{ 'bg-blue-700': dropdownOpen }"
            >
              <span>{{ displayName }}</span>
              <svg 
                class="w-4 h-4 ml-1 transition-transform duration-200" 
                :class="{ 'transform rotate-180': dropdownOpen }"
                fill="none" 
                stroke="currentColor" 
                viewBox="0 0 24 24" 
                xmlns="http://www.w3.org/2000/svg"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
              </svg>
            </button>
            
            <!-- Menu dropdown -->
            <div 
              v-show="dropdownOpen"
              class="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg py-1 z-50 border border-gray-200 transition-all duration-150 ease-in-out"
            >
              <div class="px-4 py-3 text-sm text-gray-700 border-b border-gray-100">
                <div class="font-medium text-gray-900">{{ user?.name }}</div>
                <div class="text-gray-500 truncate">{{ user?.email }}</div>
                <div class="text-gray-500 capitalize mt-1 inline-block px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">{{ user?.role }}</div>
              </div>
              
              <button 
                @click="logout" 
                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition duration-150 ease-in-out"
              >
                <div class="flex items-center">
                  <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                  </svg>
                  Sair
                </div>
              </button>
            </div>
          </div>
        </template>
        
        <!-- Links visíveis para usuários não autenticados -->
        <!-- <template v-else>
          <router-link to="/login" class="hover:text-blue-200">Login</router-link>
          <router-link to="/register" class="hover:text-blue-200">Registrar</router-link>
        </template> -->
      </div>
    </div>
  </nav>
</template>

<style scoped>
.router-link-active {
  font-weight: bold;
  text-decoration: underline;
}
</style>
