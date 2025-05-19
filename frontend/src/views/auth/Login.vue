<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useToast } from 'vue-toastification'
import authService from '../../services/authService'

const router = useRouter()
const toast = useToast()

// Estado do formulário
const email = ref('')
const password = ref('')
const loading = ref(false)
const error = ref('')
const formSubmitted = ref(false)

// Verificar se há um usuário válido ao montar o componente
onMounted(() => {
  // Remover qualquer token antigo ou inválido
  const token = localStorage.getItem('token')
  const user = localStorage.getItem('user')
  
  // Só redirecionar se houver token E dados do usuário
  if (token && user) {
    try {
      // Verificar se os dados do usuário são válidos
      const userData = JSON.parse(user)
      if (userData && userData.id) {
        // Usuário válido, redirecionar para a home
        router.push('/')
      } else {
        // Dados inválidos, limpar localStorage
        localStorage.removeItem('token')
        localStorage.removeItem('user')
      }
    } catch (e) {
      // Erro ao analisar os dados do usuário, limpar localStorage
      localStorage.removeItem('token')
      localStorage.removeItem('user')
    }
  }
})

// Função principal de login
async function submitLogin() {
  // Evitar múltiplas submissões
  if (loading.value || formSubmitted.value) return
  
  // Marcar que o formulário foi submetido
  formSubmitted.value = true
  
  // Limpar erro anterior
  error.value = ''
  
  // Validação básica
  if (!email.value.trim()) {
    error.value = 'O email é obrigatório'
    toast.error(error.value, { timeout: 5000 })
    formSubmitted.value = false
    return
  }
  
  if (!password.value) {
    error.value = 'A senha é obrigatória'
    toast.error(error.value, { timeout: 5000 })
    formSubmitted.value = false
    return
  }
  
  // Iniciar loading
  loading.value = true
  
  try {
    // Chamada à API de login
    const response = await authService.login({
      email: email.value.trim(),
      password: password.value
    })
    
    // Login bem-sucedido
    toast.success('Login realizado com sucesso!', { timeout: 3000 })
    
    // Redirecionar após um breve atraso para permitir que o toast seja visto
    setTimeout(() => {
      router.push('/')
    }, 1500)
    
  } catch (err: any) {
    console.error('Erro de login:', err)
    handleLoginError(err)
  } finally {
    loading.value = false
    // Permitir nova submissão após um breve atraso
    setTimeout(() => {
      formSubmitted.value = false
    }, 1000)
  }
}

// Função para tratar erros de login
function handleLoginError(err: any) {
  // Erro de credenciais inválidas (401)
  if (err.response && err.response.status === 401) {
    error.value = 'Credenciais inválidas'
    toast.error('Credenciais inválidas', { timeout: 7000 })
    return
  }
  
  // Erros de validação do servidor
  if (err.response && err.response.data && err.response.data.errors) {
    const validationErrors = Object.values(err.response.data.errors).flat()
    error.value = validationErrors.join(', ')
    toast.error(error.value, { timeout: 7000 })
    return
  }
  
  // Mensagem de erro específica do servidor
  if (err.response && err.response.data && err.response.data.message) {
    error.value = err.response.data.message
    toast.error(error.value, { timeout: 7000 })
    return
  }
  
  // Erro de conexão com o servidor
  if (err.message && err.message.includes('Network Error')) {
    error.value = 'Não foi possível conectar ao servidor. Verifique sua conexão.'
    toast.error(error.value, { timeout: 7000 })
    return
  }
  
  // Erro genérico
  error.value = 'Ocorreu um erro ao fazer login. Tente novamente mais tarde.'
  toast.error(error.value, { timeout: 7000 })
}
</script>

<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 to-indigo-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8 transition-all duration-300 hover:shadow-xl">
      <div class="mb-8">
        <div class="flex justify-center mb-4">
          <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center shadow-md">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
          </div>
        </div>
        <h1 class="text-center text-3xl font-extrabold text-blue-600 mb-1">StoreManager</h1>
        <h2 class="text-center text-xl font-bold text-gray-700">Entre na sua conta</h2>
      </div>
      
      <form class="space-y-5" @submit.prevent="submitLogin" novalidate>
        <div class="space-y-4">
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
              autocomplete="current-password" 
              required 
              v-model="password"
              class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 ease-in-out"
              placeholder="Senha"
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
              </svg>
              <svg v-if="loading" class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            </span>
            {{ loading ? 'Entrando...' : 'Entrar' }}
          </button>
        </div>

        <div class="text-center mt-4">
          <p class="text-sm text-gray-600">
            Não tem uma conta?
            <router-link to="/register" class="font-medium text-blue-600 hover:text-blue-500 transition duration-150 ease-in-out">
              Registre-se agora
            </router-link>
          </p>
        </div>

        <div class="mt-6 p-4 border border-gray-100 rounded-lg bg-gray-50">
          <p class="text-sm font-medium text-gray-600 mb-2">Credenciais de teste:</p>
          <div class="grid grid-cols-2 gap-2 text-sm text-gray-500">
            <div class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>Admin:</span>
            </div>
            <div>admin@example.com / password</div>
            <div class="flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
              </svg>
              <span>User:</span>
            </div>
            <div>user@example.com / password</div>
          </div>
        </div>
      </form>
    </div>
  </div>
</template>
