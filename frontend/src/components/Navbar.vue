<script setup lang="ts">
import { ref, computed, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useToast } from 'vue-toastification';
import authService from '../services/authService';

const router = useRouter();
const toast = useToast();

const isAuthenticated = ref(false);
const user = ref<any>(null);
const isAdmin = ref(false);

// Atualiza o estado de autenticação
const updateAuthState = () => {
  isAuthenticated.value = authService.isAuthenticated();
  user.value = authService.getCurrentUser();
  isAdmin.value = authService.isAdmin();
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
          <router-link to="/products" class="hover:text-blue-200">Produtos</router-link>
          <router-link to="/categories" class="hover:text-blue-200">Categorias</router-link>
          
          <!-- Links visíveis apenas para administradores -->
          <template v-if="isAdmin">
            <router-link to="/users" class="hover:text-blue-200">Usuários</router-link>
          </template>
          
          <!-- Dropdown de usuário -->
          <div class="relative group">
            <button class="flex items-center hover:text-blue-200">
              <span>{{ displayName }}</span>
              <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
              </svg>
            </button>
            <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-10 hidden group-hover:block">
              <div class="px-4 py-2 text-sm text-gray-700">
                <div class="font-medium">{{ user?.name }}</div>
                <div class="text-gray-500">{{ user?.email }}</div>
                <div class="text-gray-500 capitalize">{{ user?.role }}</div>
              </div>
              <hr class="my-1">
              <button 
                @click="logout" 
                class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
              >
                Sair
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
