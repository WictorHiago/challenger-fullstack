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
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8 transition-all duration-300 hover:shadow-xl">
      <div class="mb-8">
        <div class="flex justify-center mb-4">
          <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
          </div>
        </div>
        <h1 class="text-center text-3xl font-extrabold text-blue-600 mb-1">StoreManager</h1>
        <h2 class="text-center text-xl font-bold text-gray-700">Crie sua conta</h2>
      </div>
      
      <form class="space-y-5" @submit.prevent="register">
        <div class="space-y-4">
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
            </div>
            <input 
              id="name" 
              name="name" 
              type="text" 
              required 
              v-model="name"
              class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 ease-in-out"
              placeholder="Nome completo"
            />
          </div>
          
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
              </svg>
            </div>
            <input 
              id="email-address" 
              name="email" 
              type="email" 
              autocomplete="email" 
              required 
              v-model="email"
              class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 ease-in-out"
              placeholder="Email"
            />
          </div>
          
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
              </svg>
            </div>
            <input 
              id="password" 
              name="password" 
              type="password" 
              autocomplete="new-password" 
              required 
              v-model="password"
              class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 ease-in-out"
              placeholder="Senha"
            />
          </div>
          
          <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
              </svg>
            </div>
            <input 
              id="password-confirmation" 
              name="password-confirmation" 
              type="password" 
              autocomplete="new-password" 
              required 
              v-model="passwordConfirmation"
              class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 ease-in-out"
              placeholder="Confirmar senha"
            />
          </div>
        </div>

        <div class="text-red-500 text-sm font-medium p-3 mb-1 bg-red-50 rounded-lg" v-if="error">
          <div class="flex items-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            {{ error }}
          </div>
        </div>

        <div>
          <button 
            type="submit" 
            class="group relative w-full flex justify-center py-3 px-4 border border-transparent rounded-lg text-white font-medium bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out"
            :disabled="loading"
          >
            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
              <svg v-if="!loading" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-400 group-hover:text-blue-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
              </svg>
              <svg v-if="loading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            </span>
            {{ loading ? 'Registrando...' : 'Criar conta' }}
          </button>
        </div>

        <div class="text-center mt-4">
          <p class="text-sm text-gray-600">
            Já tem uma conta?
            <router-link to="/login" class="font-medium text-blue-600 hover:text-blue-500 transition duration-150 ease-in-out">
              Faça login agora
            </router-link>
          </p>
        </div>
      </form>
    </div>
  </div>
</template>
