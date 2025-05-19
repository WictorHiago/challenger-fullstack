<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useToast } from 'vue-toastification'
import categoryService, { Category } from '../../services/categoryService'
import authService from '../../services/authService'

const router = useRouter()
const toast = useToast()

// Estado
const categories = ref<Category[]>([])

const loading = ref(false)
const searchTerm = ref('')
const showDeleteModal = ref(false)
const categoryToDelete = ref<Category | null>(null)

// Dados filtrados
const filteredCategories = computed(() => {
  if (!searchTerm.value) return categories.value
  
  const term = searchTerm.value.toLowerCase()
  return categories.value.filter(category => 
    category.name.toLowerCase().includes(term) || 
    category.description.toLowerCase().includes(term)
  )
})

// Verificar permissões do usuário usando authService
const currentUser = computed(() => authService.getCurrentUser())
const userRole = computed(() => currentUser.value?.role || '')
const canEdit = computed(() => userRole.value === 'admin')
const canDelete = computed(() => userRole.value === 'admin')
const canCreate = computed(() => userRole.value === 'admin') // Apenas admin pode criar categorias

// Carregar categorias da API
onMounted(async () => {
  await fetchCategories()
})

async function fetchCategories() {
  loading.value = true
  try {
    console.log('Token atual:', localStorage.getItem('token'))
    console.log('Usuário atual:', localStorage.getItem('user'))
    console.log('Iniciando busca de categorias...')
    
    categories.value = await categoryService.getCategories()
    console.log('Categorias carregadas com sucesso:', categories.value)
  } catch (error: any) {
    console.error('Erro ao carregar categorias:', error)
    
    // Tratamento mais detalhado do erro
    if (error.response) {
      // O servidor respondeu com um status de erro
      console.error('Status do erro:', error.response.status)
      console.error('Dados do erro:', error.response.data)
      
      if (error.response.status === 500) {
        toast.error('Erro interno no servidor. Contate o administrador.')
      } else if (error.response.status === 401) {
        toast.error('Sessão expirada. Faça login novamente.')
        // Redirecionar para login após um breve atraso
        setTimeout(() => {
          router.push('/login')
        }, 2000)
      } else {
        toast.error(`Erro ao carregar categorias: ${error.response.data.message || 'Tente novamente mais tarde.'}`)
      }
    } else if (error.request) {
      // A requisição foi feita mas não houve resposta
      console.error('Sem resposta do servidor:', error.request)
      toast.error('Não foi possível conectar ao servidor. Verifique sua conexão.')
    } else {
      // Erro na configuração da requisição
      console.error('Erro na configuração da requisição:', error.message)
      toast.error('Erro ao preparar a requisição. Tente novamente.')
    }
  } finally {
    loading.value = false
  }
}

// Métodos
const confirmDelete = (category: Category) => {
  categoryToDelete.value = category
  showDeleteModal.value = true
}

const deleteCategory = async () => {
  if (!categoryToDelete.value) return
  
  loading.value = true
  try {
    await categoryService.deleteCategory(categoryToDelete.value.id)
    toast.success('Categoria excluída com sucesso!')
    await fetchCategories() // Recarregar a lista após excluir
  } catch (error) {
    console.error('Erro ao excluir categoria:', error)
    toast.error('Erro ao excluir categoria. Tente novamente mais tarde.')
  } finally {
    showDeleteModal.value = false
    categoryToDelete.value = null
    loading.value = false
  }
}

const cancelDelete = () => {
  showDeleteModal.value = false
  categoryToDelete.value = null
}
</script>

<template>
  <div>
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Categorias</h1>
        <p class="mt-1 text-sm text-gray-600">Gerencie todas as categorias disponíveis no sistema.</p>
      </div>
      <div class="mt-4 sm:mt-0">
        <router-link 
          v-if="canCreate" 
          to="/categories/create" 
          class="btn btn-primary flex items-center"
        >
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
          </svg>
          Nova Categoria
        </router-link>
      </div>
    </div>

    <!-- Search -->
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
              id="search"
              v-model="searchTerm"
              class="form-input pl-10"
              placeholder="Buscar categorias por nome ou descrição"
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
      <span class="ml-3 text-lg text-gray-700">Carregando categorias...</span>
    </div>
    
    <!-- Categories Table -->
    <div v-else class="bg-white shadow rounded-lg overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Nome
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Descrição
              </th>
              <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                Ações
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="category in filteredCategories" :key="category.id">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ category.name }}</div>
              </td>
              <td class="px-6 py-4">
                <div class="text-sm text-gray-500">{{ category.description }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex justify-end space-x-3">
                  <router-link 
                    v-if="canEdit"
                    :to="`/categories/${category.id}/edit`" 
                    class="text-indigo-600 hover:text-indigo-900 flex items-center"
                    title="Editar categoria"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    <span class="ml-1">Editar</span>
                  </router-link>
                  <button 
                    v-if="canDelete"
                    @click="confirmDelete(category)" 
                    class="text-red-600 hover:text-red-900 flex items-center"
                    title="Excluir categoria"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    <span class="ml-1">Excluir</span>
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="filteredCategories.length === 0">
              <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                Nenhuma categoria encontrada.
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Delete Confirmation Modal - Versão simplificada -->
    <div v-if="showDeleteModal" class="fixed inset-0 flex items-center justify-center z-50" style="background-color: rgba(0,0,0,0.5);">
      <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <div class="flex items-start mb-4">
          <div class="flex-shrink-0 bg-red-100 rounded-full p-2 mr-3">
            <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
          </div>
          <div>
            <h3 class="text-lg font-medium text-gray-900">Excluir categoria</h3>
            <p class="mt-2 text-sm text-gray-500">
              Tem certeza que deseja excluir a categoria "{{ categoryToDelete?.name }}"? Esta ação não pode ser desfeita.
            </p>
            <p class="mt-2 text-sm text-red-500">
              Atenção: Excluir esta categoria pode afetar produtos associados a ela.
            </p>
          </div>
        </div>
        <div class="flex justify-end space-x-3 mt-6">
          <button 
            type="button" 
            class="px-4 py-2 bg-white border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none"
            @click="cancelDelete"
          >
            Cancelar
          </button>
          <button 
            type="button" 
            class="px-4 py-2 bg-red-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-red-700 focus:outline-none"
            @click="deleteCategory"
          >
            Excluir
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
