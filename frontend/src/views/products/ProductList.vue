<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useToast } from 'vue-toastification'

const router = useRouter()
const toast = useToast()

// Estado
const products = ref([
  { id: 1, name: 'Smartphone XYZ', description: 'Um smartphone de última geração', price: 1999.90, image_url: 'https://via.placeholder.com/150', category: { id: 1, name: 'Eletrônicos' } },
  { id: 2, name: 'Notebook Ultra', description: 'Notebook leve e potente', price: 4599.90, image_url: 'https://via.placeholder.com/150', category: { id: 2, name: 'Computadores' } },
  { id: 3, name: 'Monitor 27"', description: 'Monitor de alta resolução', price: 1299.90, image_url: 'https://via.placeholder.com/150', category: { id: 3, name: 'Periféricos' } },
  { id: 4, name: 'Teclado Mecânico', description: 'Teclado para gamers', price: 349.90, image_url: 'https://via.placeholder.com/150', category: { id: 3, name: 'Periféricos' } },
  { id: 5, name: 'Mouse Gamer', description: 'Mouse de alta precisão', price: 199.90, image_url: 'https://via.placeholder.com/150', category: { id: 3, name: 'Periféricos' } },
  { id: 6, name: 'Headset Wireless', description: 'Headset sem fio com cancelamento de ruído', price: 599.90, image_url: 'https://via.placeholder.com/150', category: { id: 3, name: 'Periféricos' } },
  { id: 7, name: 'Smart TV 55"', description: 'TV com resolução 4K', price: 3299.90, image_url: 'https://via.placeholder.com/150', category: { id: 1, name: 'Eletrônicos' } },
  { id: 8, name: 'Tablet Pro', description: 'Tablet com tela retina', price: 2499.90, image_url: 'https://via.placeholder.com/150', category: { id: 1, name: 'Eletrônicos' } }
])

const loading = ref(false)
const searchTerm = ref('')
const showDeleteModal = ref(false)
const productToDelete = ref(null)

// Dados paginados e filtrados
const filteredProducts = computed(() => {
  if (!searchTerm.value) return products.value
  
  const term = searchTerm.value.toLowerCase()
  return products.value.filter(product => 
    product.name.toLowerCase().includes(term) || 
    product.description.toLowerCase().includes(term) ||
    product.category.name.toLowerCase().includes(term)
  )
})

// Verificar permissões do usuário
const userRole = computed(() => localStorage.getItem('userRole') || '')
const canEdit = computed(() => userRole.value === 'admin')
const canDelete = computed(() => userRole.value === 'admin')
const canCreate = computed(() => ['admin', 'user'].includes(userRole.value))

// Métodos
const confirmDelete = (product) => {
  productToDelete.value = product
  showDeleteModal.value = true
}

const deleteProduct = () => {
  if (!productToDelete.value) return
  
  // Simulando deleção
  products.value = products.value.filter(p => p.id !== productToDelete.value.id)
  
  toast.success('Produto excluído com sucesso!')
  showDeleteModal.value = false
  productToDelete.value = null
}

const cancelDelete = () => {
  showDeleteModal.value = false
  productToDelete.value = null
}

const formatPrice = (price) => {
  return new Intl.NumberFormat('pt-BR', {
    style: 'currency',
    currency: 'BRL'
  }).format(price)
}
</script>

<template>
  <div>
    <!-- Header -->
    <div class="mb-6 flex flex-col sm:flex-row justify-between items-start sm:items-center">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Produtos</h1>
        <p class="mt-1 text-sm text-gray-600">Gerencie todos os produtos disponíveis no sistema.</p>
      </div>
      <div class="mt-4 sm:mt-0">
        <router-link 
          v-if="canCreate" 
          to="/products/create" 
          class="btn btn-primary flex items-center"
        >
          <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
          </svg>
          Novo Produto
        </router-link>
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
              id="search"
              v-model="searchTerm"
              class="form-input pl-10"
              placeholder="Buscar produtos por nome, descrição ou categoria"
              type="text"
            />
          </div>
        </div>
      </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Produto
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Categoria
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Preço
              </th>
              <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                Ações
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="product in filteredProducts" :key="product.id">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0 h-10 w-10">
                    <img class="h-10 w-10 rounded-full object-cover" :src="product.image_url" alt="" />
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ product.name }}</div>
                    <div class="text-sm text-gray-500">{{ product.description }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ product.category.name }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ formatPrice(product.price) }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <div class="flex justify-end space-x-2">
                  <router-link 
                    v-if="canEdit"
                    :to="`/products/${product.id}/edit`" 
                    class="text-indigo-600 hover:text-indigo-900"
                  >
                    Editar
                  </router-link>
                  <button 
                    v-if="canDelete"
                    @click="confirmDelete(product)" 
                    class="text-red-600 hover:text-red-900"
                  >
                    Excluir
                  </button>
                </div>
              </td>
            </tr>
            <tr v-if="filteredProducts.length === 0">
              <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                Nenhum produto encontrado.
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed z-10 inset-0 overflow-y-auto">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
          <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="sm:flex sm:items-start">
              <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                <svg class="h-6 w-6 text-red-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
              </div>
              <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                  Excluir produto
                </h3>
                <div class="mt-2">
                  <p class="text-sm text-gray-500">
                    Tem certeza que deseja excluir o produto "{{ productToDelete?.name }}"? Esta ação não pode ser desfeita.
                  </p>
                </div>
              </div>
            </div>
          </div>
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button @click="deleteProduct" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
              Excluir
            </button>
            <button @click="cancelDelete" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
              Cancelar
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
