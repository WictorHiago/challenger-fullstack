<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useToast } from 'vue-toastification'
import productService from '../../services/productService'
import categoryService from '../../services/categoryService'
import authService from '../../services/authService'

const router = useRouter()
const route = useRoute()
const toast = useToast()

// Verificar permissões do usuário
const currentUser = computed(() => authService.getCurrentUser())
const userRole = computed(() => currentUser.value?.role || '')
const isAdmin = computed(() => userRole.value === 'admin')

// Redirecionar se não for admin
onMounted(() => {
  if (!isAdmin.value) {
    toast.error('Você não tem permissão para acessar esta página')
    router.push('/products')
    return
  }
  
  // Carregar categorias
  fetchCategories()
  
  // Carregar produto se estiver editando
  if (isEditing.value) {
    loadProduct()
  }
})

// Determinar se estamos editando ou criando
const isEditing = computed(() => route.params.id !== undefined)
const pageTitle = computed(() => isEditing.value ? 'Editar Produto' : 'Novo Produto')

// Estado do formulário
const product = ref({
  id: null,
  name: '',
  description: '',
  price: '',
  image_url: '',
  category_id: ''
})

// Dados de categorias da API
const categories = ref([])
const loading = ref(false)
const errors = ref({})

// Carregar categorias da API
async function fetchCategories() {
  try {
    const data = await categoryService.getCategories()
    categories.value = data
  } catch (error) {
    console.error('Erro ao carregar categorias:', error)
    toast.error('Erro ao carregar categorias. Tente novamente mais tarde.')
  }
}

const loadProduct = async () => {
  loading.value = true
  
  try {
    const productId = Number(route.params.id)
    const data = await productService.getProduct(productId)
    
    if (data) {
      product.value = { 
        ...data,
        price: data.price.toString(),
        category_id: data.category?.id || ''
      }
    } else {
      toast.error('Produto não encontrado')
      router.push('/products')
    }
  } catch (error) {
    console.error('Erro ao carregar o produto:', error)
    toast.error('Erro ao carregar o produto. Tente novamente mais tarde.')
    router.push('/products')
  } finally {
    loading.value = false
  }
}

const validateForm = () => {
  const newErrors = {}
  
  if (!product.value.name) {
    newErrors.name = 'O nome do produto é obrigatório'
  }
  
  if (!product.value.description) {
    newErrors.description = 'A descrição do produto é obrigatória'
  }
  
  if (!product.value.price) {
    newErrors.price = 'O preço do produto é obrigatório'
  } else if (isNaN(parseFloat(product.value.price)) || parseFloat(product.value.price) <= 0) {
    newErrors.price = 'O preço deve ser um número positivo'
  }
  
  if (!product.value.category_id) {
    newErrors.category_id = 'A categoria do produto é obrigatória'
  }
  
  if (!product.value.image_url) {
    newErrors.image_url = 'A URL da imagem do produto é obrigatória'
  } else if (!/^https?:\/\/.+/.test(product.value.image_url)) {
    newErrors.image_url = 'A URL da imagem deve começar com http:// ou https://'
  }
  
  errors.value = newErrors
  return Object.keys(newErrors).length === 0
}

const saveProduct = async () => {
  if (!validateForm()) {
    toast.error('Por favor, corrija os erros no formulário')
    return
  }
  
  loading.value = true
  
  try {
    // Preparar dados do produto
    const productData = {
      ...product.value,
      price: parseFloat(product.value.price)
    }
    
    // Salvar ou atualizar o produto
    if (isEditing.value) {
      await productService.updateProduct(product.value.id, productData)
      toast.success('Produto atualizado com sucesso!')
    } else {
      await productService.createProduct(productData)
      toast.success('Produto criado com sucesso!')
    }
    
    router.push('/products')
  } catch (error) {
    console.error('Erro ao salvar o produto:', error)
    if (error.response && error.response.data && error.response.data.errors) {
      // Mapear erros de validação da API
      const apiErrors = error.response.data.errors
      Object.keys(apiErrors).forEach(key => {
        errors.value[key] = apiErrors[key][0]
      })
      toast.error('Por favor, corrija os erros no formulário')
    } else {
      toast.error('Erro ao salvar o produto. Tente novamente mais tarde.')
    }
  } finally {
    loading.value = false
  }
}

const cancel = () => {
  router.push('/products')
}

// Formatar entrada de preço para garantir formato correto (00.00)
const formatPriceInput = (event) => {
  let value = event.target.value;
  
  // Remove todos os caracteres não numéricos, exceto ponto
  value = value.replace(/[^0-9.]/g, '');
  
  // Garante que só existe um ponto decimal
  const parts = value.split('.');
  if (parts.length > 2) {
    value = parts[0] + '.' + parts.slice(1).join('');
  }
  
  // Limita a 2 casas decimais
  if (parts.length > 1 && parts[1].length > 2) {
    value = parts[0] + '.' + parts[1].substring(0, 2);
  }
  
  // Limita o valor a 9999999.99
  if (parseFloat(value) > 9999999.99) {
    value = '9999999.99';
  }
  
  // Atualiza o valor
  product.value.price = value;
}
</script>

<template>
  <div>
    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">{{ pageTitle }}</h1>
      <p class="mt-1 text-sm text-gray-600">
        {{ isEditing ? 'Edite as informações do produto existente.' : 'Preencha as informações para criar um novo produto.' }}
      </p>
    </div>

    <!-- Loading Spinner -->
    <div v-if="loading" class="flex justify-center items-center py-12">
      <svg class="animate-spin h-10 w-10 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
      </svg>
      <span class="ml-3 text-lg text-gray-700">{{ isEditing ? 'Carregando produto...' : 'Preparando formulário...' }}</span>
    </div>
    
    <!-- Form -->
    <div v-else class="bg-white shadow rounded-lg overflow-hidden">
      <form @submit.prevent="saveProduct" class="p-6 space-y-6">
        <!-- Name -->
        <div>
          <label for="name" class="form-label">Nome do Produto</label>
          <input
            id="name"
            v-model="product.name"
            type="text"
            class="form-input"
            :class="{ 'border-red-500': errors.name }"
            placeholder="Nome do produto"
          />
          <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
        </div>

        <!-- Description -->
        <div>
          <label for="description" class="form-label">Descrição</label>
          <textarea
            id="description"
            v-model="product.description"
            rows="3"
            class="form-input"
            :class="{ 'border-red-500': errors.description }"
            placeholder="Descrição detalhada do produto"
          ></textarea>
          <p v-if="errors.description" class="mt-1 text-sm text-red-600">{{ errors.description }}</p>
        </div>

        <!-- Price -->
        <div>
          <label for="price" class="form-label">Preço (R$)</label>
          <input
            id="price"
            v-model="product.price"
            type="text"
            class="form-input"
            :class="{ 'border-red-500': errors.price }"
            placeholder="0.00"
            @input="formatPriceInput"
            pattern="^\d+(\.\d{1,2})?$"
            title="Insira um valor no formato 0.00"
          />
          <p v-if="errors.price" class="mt-1 text-sm text-red-600">{{ errors.price }}</p>
          <p class="mt-1 text-sm text-gray-500">Formato: 0.00 (use ponto como separador decimal)</p>
        </div>

        <!-- Category -->
        <div>
          <label for="category" class="form-label">Categoria</label>
          <select
            id="category"
            v-model="product.category_id"
            class="form-input"
            :class="{ 'border-red-500': errors.category_id }"
          >
            <option value="" disabled>Selecione uma categoria</option>
            <option v-for="category in categories" :key="category.id" :value="category.id">
              {{ category.name }}
            </option>
          </select>
          <p v-if="errors.category_id" class="mt-1 text-sm text-red-600">{{ errors.category_id }}</p>
        </div>

        <!-- Image URL -->
        <div>
          <label for="image_url" class="form-label">URL da Imagem</label>
          <input
            id="image_url"
            v-model="product.image_url"
            type="text"
            class="form-input"
            :class="{ 'border-red-500': errors.image_url }"
            placeholder="https://exemplo.com/imagem.jpg"
          />
          <p v-if="errors.image_url" class="mt-1 text-sm text-red-600">{{ errors.image_url }}</p>
          <p class="mt-1 text-sm text-gray-500">Insira a URL completa da imagem do produto</p>
        </div>

        <!-- Image Preview -->
        <div v-if="product.image_url" class="mt-4">
          <label class="form-label">Pré-visualização da Imagem</label>
          <div class="mt-2 border rounded-lg overflow-hidden w-40 h-40">
            <img :src="product.image_url" alt="Preview" class="w-full h-full object-cover" />
          </div>
        </div>

        <!-- Form Actions -->
        <div class="flex justify-end space-x-3 pt-4">
          <button
            type="button"
            @click="cancel"
            class="btn btn-secondary"
          >
            Cancelar
          </button>
          <button
            type="submit"
            class="btn btn-primary"
            :disabled="loading"
          >
            <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ loading ? 'Salvando...' : 'Salvar' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
