<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useToast } from 'vue-toastification'
import categoryService, { Category, CategoryFormData } from '../../services/categoryService'
import authService from '../../services/authService'

const router = useRouter()
const route = useRoute()
const toast = useToast()

// Determinar se estamos editando ou criando
const isEditing = computed(() => route.params.id !== undefined)
const pageTitle = computed(() => isEditing.value ? 'Editar Categoria' : 'Nova Categoria')

// Estado do formulário
const category = ref<{
  id: number | null;
  name: string;
  description?: string;
}>({ 
  id: null, 
  name: '', 
  description: '' 
})

const loading = ref(false)
const errors = ref({})

// Carregar dados da categoria se estiver editando
onMounted(() => {
  if (isEditing.value) {
    loadCategory()
  }
})

const loadCategory = async () => {
  loading.value = true
  
  try {
    const categoryId = Number(route.params.id)
    const foundCategory = await categoryService.getCategory(categoryId)
    
    if (foundCategory) {
      category.value = { 
        id: foundCategory.id,
        name: foundCategory.name,
        description: ''
      }
    } else {
      toast.error('Categoria não encontrada')
      router.push('/categories')
    }
  } catch (error) {
    toast.error('Erro ao carregar a categoria')
  } finally {
    loading.value = false
  }
}

const validateForm = () => {
  const newErrors: Record<string, string> = {}
  
  if (!category.value.name) {
    newErrors.name = 'O nome da categoria é obrigatório'
  }
  
  errors.value = newErrors
  return Object.keys(newErrors).length === 0
}

const saveCategory = async () => {
  if (!validateForm()) {
    toast.error('Por favor, corrija os erros no formulário')
    return
  }
  
  loading.value = true
  
  try {
    const formData: CategoryFormData = {
      name: category.value.name
    }
    
    if (isEditing.value && category.value.id) {
      await categoryService.updateCategory(category.value.id, formData)
      toast.success('Categoria atualizada com sucesso!')
    } else {
      await categoryService.createCategory(formData)
      toast.success('Categoria criada com sucesso!')
    }
    
    router.push('/categories')
  } catch (error: any) {
    console.error('Erro ao salvar categoria:', error)
    
    if (error.response && error.response.data && error.response.data.errors) {
      // Erros de validação do servidor
      const serverErrors = error.response.data.errors
      const newErrors: Record<string, string> = {}
      
      if (serverErrors.name) {
        newErrors.name = serverErrors.name[0]
      }
      
      errors.value = newErrors
      toast.error('Por favor, corrija os erros no formulário')
    } else {
      toast.error('Erro ao salvar a categoria. Tente novamente mais tarde.')
    }
  } finally {
    loading.value = false
  }
}

const cancel = () => {
  router.push('/categories')
}
</script>

<template>
  <div>
    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">{{ pageTitle }}</h1>
      <p class="mt-1 text-sm text-gray-600">
        {{ isEditing ? 'Edite as informações da categoria existente.' : 'Preencha as informações para criar uma nova categoria.' }}
      </p>
    </div>

    <!-- Form -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
      <form @submit.prevent="saveCategory" class="p-6 space-y-6">
        <!-- Name -->
        <div>
          <label for="name" class="form-label">Nome da Categoria</label>
          <input
            id="name"
            v-model="category.name"
            type="text"
            class="form-input"
            :class="{ 'border-red-500': errors.name }"
            placeholder="Nome da categoria"
          />
          <p v-if="errors.name" class="mt-1 text-sm text-red-600">{{ errors.name }}</p>
        </div>

        <!-- Description -->
        <div>
          <label for="description" class="form-label">Descrição</label>
          <textarea
            id="description"
            v-model="category.description"
            rows="3"
            class="form-input"
            :class="{ 'border-red-500': errors.description }"
            placeholder="Descrição detalhada da categoria"
          ></textarea>
          <p v-if="errors.description" class="mt-1 text-sm text-red-600">{{ errors.description }}</p>
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
