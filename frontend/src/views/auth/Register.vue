<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useToast } from 'vue-toastification'
import authService from '../../services/authService'

const router = useRouter()
const toast = useToast()

const name = ref('')
const email = ref('')
const password = ref('')
const passwordConfirmation = ref('')
const loading = ref(false)
const error = ref('')

const register = async () => {
  // Validação básica
  if (!name.value || !email.value || !password.value || !passwordConfirmation.value) {
    error.value = 'Todos os campos são obrigatórios'
    return
  }

  if (password.value !== passwordConfirmation.value) {
    error.value = 'As senhas não coincidem'
    return
  }

  try {
    loading.value = true
    error.value = ''
    
    // Chamada à API de registro usando o authService
    const response = await authService.register({
      name: name.value,
      email: email.value,
      password: password.value,
      password_confirmation: passwordConfirmation.value,
      role: 'user' // Por padrão, novos usuários são registrados como 'user'
    })
    
    toast.success('Registro realizado com sucesso!')
    router.push('/')
  } catch (err: any) {
    console.error('Erro de registro:', err)
    
    if (err.response && err.response.data) {
      // Tratamento de erros da API
      if (err.response.data.errors) {
        // Erros de validação
        const validationErrors = Object.values(err.response.data.errors).flat()
        error.value = validationErrors.join(', ')
        toast.error(error.value)
      } else if (err.response.data.message) {
        error.value = err.response.data.message
        toast.error(error.value)
      } else {
        error.value = 'Ocorreu um erro ao fazer o registro'
        toast.error('Ocorreu um erro ao fazer o registro')
      }
    } else {
      error.value = 'Ocorreu um erro ao fazer o registro'
      toast.error('Ocorreu um erro ao fazer o registro')
    }
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div>
        <h1 class="text-center text-3xl font-extrabold text-blue-600">StoreManager</h1>
        <h2 class="mt-6 text-center text-2xl font-bold text-gray-900">Crie sua conta</h2>
      </div>
      <form class="mt-8 space-y-6" @submit.prevent="register">
        <div class="rounded-md shadow-sm -space-y-px">
          <div>
            <label for="name" class="sr-only">Nome</label>
            <input 
              id="name" 
              name="name" 
              type="text" 
              required 
              v-model="name"
              class="form-input rounded-t-md"
              placeholder="Nome completo"
            />
          </div>
          <div>
            <label for="email-address" class="sr-only">Email</label>
            <input 
              id="email-address" 
              name="email" 
              type="email" 
              autocomplete="email" 
              required 
              v-model="email"
              class="form-input"
              placeholder="Email"
            />
          </div>
          <div>
            <label for="password" class="sr-only">Senha</label>
            <input 
              id="password" 
              name="password" 
              type="password" 
              autocomplete="new-password" 
              required 
              v-model="password"
              class="form-input"
              placeholder="Senha"
            />
          </div>
          <div>
            <label for="password-confirmation" class="sr-only">Confirmar Senha</label>
            <input 
              id="password-confirmation" 
              name="password-confirmation" 
              type="password" 
              autocomplete="new-password" 
              required 
              v-model="passwordConfirmation"
              class="form-input rounded-b-md"
              placeholder="Confirmar senha"
            />
          </div>
        </div>

        <div class="text-red-500 text-sm" v-if="error">
          {{ error }}
        </div>

        <div>
          <button 
            type="submit" 
            class="btn btn-primary w-full flex justify-center"
            :disabled="loading"
          >
            <svg v-if="loading" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ loading ? 'Registrando...' : 'Registrar' }}
          </button>
        </div>

        <div class="text-center">
          <p class="text-sm text-gray-600">
            Já tem uma conta?
            <router-link to="/login" class="font-medium text-blue-600 hover:text-blue-500">
              Faça login
            </router-link>
          </p>
        </div>
      </form>
    </div>
  </div>
</template>
