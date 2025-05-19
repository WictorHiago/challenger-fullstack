<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import productService from '../services/productService'
import categoryService from '../services/categoryService'
import authService from '../services/authService'

// Dados do dashboard
const stats = ref([
  { name: 'Total de Produtos', value: '0', icon: 'box' },
  { name: 'Total de Categorias', value: '0', icon: 'tag' },
  { name: 'Vendas do Mês', value: 'R$ 0,00', icon: 'chart' },
  { name: 'Usuários Ativos', value: '0', icon: 'user' }
])

const recentProducts = ref([])
const loading = ref(true)

// Verificar autenticação
const isLoggedIn = computed(() => authService.isAuthenticated())
const currentUser = computed(() => authService.getCurrentUser())
const userRole = computed(() => currentUser.value?.role || '')

// Carregar dados ao montar o componente
onMounted(async () => {
  if (isLoggedIn.value) {
    try {
      // Carregar produtos
      const productsResponse = await productService.getProducts()
      if (productsResponse && productsResponse.data) {
        // Atualizar estatísticas
        stats.value[0].value = productsResponse.total?.toString() || productsResponse.data.length.toString()
        
        // Obter produtos recentes (últimos 5)
        recentProducts.value = productsResponse.data.slice(0, 5).map(product => ({
          id: product.id,
          name: product.name,
          category: product.category?.name || 'Sem categoria',
          price: `R$ ${Number(product.price).toFixed(2).replace('.', ',')}`,
          created_at: new Date(product.created_at).toLocaleDateString('pt-BR')
        }))
      }
      
      // Carregar categorias
      const categoriesResponse = await categoryService.getCategories()
      if (categoriesResponse) {
        stats.value[1].value = categoriesResponse.length.toString()
      }
    } catch (error) {
      console.error('Erro ao carregar dados do dashboard:', error)
    } finally {
      loading.value = false
    }
  }
})
</script>

<template>
  <div>
    <div v-if="isLoggedIn" class="space-y-6">
      <!-- Welcome Section -->
      <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold text-gray-900">Bem-vindo ao StoreManager</h1>
        <p class="mt-2 text-gray-600">
          Sistema de gerenciamento de produtos e categorias.
          <span v-if="userRole === 'admin'" class="font-medium text-blue-600">
            Você está logado como administrador.
          </span>
          <span v-else class="font-medium text-green-600">
            Você está logado como usuário.
          </span>
        </p>
      </div>

      <!-- Stats Grid -->
      <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
        <div v-for="(item, index) in stats" :key="index" class="bg-white overflow-hidden shadow rounded-lg">
          <div class="p-5">
            <div class="flex items-center">
              <div class="flex-shrink-0">
                <!-- Box Icon -->
                <svg v-if="item.icon === 'box'" class="h-6 w-6 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10" />
                </svg>
                <!-- Tag Icon -->
                <svg v-if="item.icon === 'tag'" class="h-6 w-6 text-green-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                </svg>
                <!-- Chart Icon -->
                <svg v-if="item.icon === 'chart'" class="h-6 w-6 text-purple-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                <!-- User Icon -->
                <svg v-if="item.icon === 'user'" class="h-6 w-6 text-yellow-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
              </div>
              <div class="ml-5 w-0 flex-1">
                <dt class="text-sm font-medium text-gray-500 truncate">
                  {{ item.name }}
                </dt>
                <dd class="flex items-baseline">
                  <div class="text-2xl font-semibold text-gray-900">
                    {{ item.value }}
                  </div>
                </dd>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Products Table -->
      <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
          <h3 class="text-lg leading-6 font-medium text-gray-900">Produtos Recentes</h3>
          <router-link to="/products" class="text-sm font-medium text-blue-600 hover:text-blue-500">
            Ver todos
          </router-link>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Nome
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Categoria
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Preço
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Data de Criação
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="product in recentProducts" :key="product.id">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ product.name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-500">{{ product.category }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ product.price }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ product.created_at }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Not Logged In State -->
    <div v-else class="bg-white rounded-lg shadow p-8 text-center">
      <h1 class="text-3xl font-bold text-gray-900 mb-4">Bem-vindo ao StoreManager</h1>
      <p class="text-lg text-gray-600 mb-8">
        Sistema completo para gerenciamento de produtos e categorias.
      </p>
      <div class="flex justify-center space-x-4">
        <router-link to="/login" class="btn btn-primary">
          Entrar
        </router-link>
        <router-link to="/register" class="btn btn-secondary">
          Registrar
        </router-link>
      </div>
    </div>
  </div>
</template>
