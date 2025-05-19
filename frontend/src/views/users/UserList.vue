<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useToast } from 'vue-toastification'
import userService from '../../services/userService'
import authService from '../../services/authService'

const router = useRouter()
const toast = useToast()

// Estado
const users = ref([])
const loading = ref(true)
const searchTerm = ref('')

// Dados filtrados
const filteredUsers = computed(() => {
  if (!searchTerm.value) return users.value
  
  const term = searchTerm.value.toLowerCase()
  return users.value.filter(user => 
    user.name.toLowerCase().includes(term) || 
    user.email.toLowerCase().includes(term) || 
    user.role.toLowerCase().includes(term)
  )
})

// Verificar permissões do usuário
const currentUser = computed(() => authService.getCurrentUser())
const userRole = computed(() => currentUser.value?.role || '')
const isAdmin = computed(() => userRole.value === 'admin')

// Carregar usuários da API
onMounted(async () => {
  // Verificar se o usuário é admin
  if (!isAdmin.value) {
    toast.error('Você não tem permissão para acessar esta página')
    router.push('/')
    return
  }
  
  await fetchUsers()
})

async function fetchUsers() {
  loading.value = true
  try {
    const data = await userService.getUsers()
    users.value = data
    console.log('Usuários carregados:', users.value)
  } catch (error) {
    console.error('Erro ao carregar usuários:', error)
    toast.error('Erro ao carregar usuários. Tente novamente mais tarde.')
    users.value = []
  } finally {
    loading.value = false
  }
}

// Formatar data
const formatDate = (dateString) => {
  if (!dateString) return 'N/A'
  
  const date = new Date(dateString)
  return new Intl.DateTimeFormat('pt-BR', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }).format(date)
}
</script>

<template>
  <div>
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Usuários</h1>
        <p class="mt-1 text-sm text-gray-600">Gerencie todos os usuários do sistema.</p>
      </div>
    </div>

    <!-- Search and Filters -->
    <div class="mb-6 bg-white rounded-lg shadow p-4">
      <div class="flex flex-col sm:flex-row gap-4">
        <div class="flex-grow">
          <label for="search" class="sr-only">Buscar</label>
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
              </svg>
            </div>
            <input
              v-model="searchTerm"
              id="search"
              class="form-input pl-10"
              placeholder="Buscar usuários por nome, email ou função"
              type="text"
            />
          </div>
        </div>
      </div>
    </div>

    <!-- Loading Spinner -->
    <div v-if="loading" class="flex justify-center items-center py-12">
      <svg class="animate-spin h-10 w-10 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
      <span class="ml-3 text-lg text-gray-700">Carregando usuários...</span>
    </div>
    
    <!-- Users Table -->
    <div v-else class="bg-white shadow rounded-lg overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Usuário
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Função
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Criado em
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="user in filteredUsers" :key="user.id">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10 bg-gray-200 rounded-full flex items-center justify-center">
                    <span class="text-gray-500 font-medium">{{ user.name.charAt(0).toUpperCase() }}</span>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
                    <div class="text-sm text-gray-500">{{ user.email }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                  :class="{
                    'bg-green-100 text-green-800': user.role === 'admin',
                    'bg-blue-100 text-blue-800': user.role === 'user'
                  }">
                  {{ user.role === 'admin' ? 'Administrador' : 'Usuário' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ formatDate(user.created_at) }}
              </td>
            </tr>
            <tr v-if="filteredUsers.length === 0">
              <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                Nenhum usuário encontrado.
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
