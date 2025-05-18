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
    // setTimeout(() => {
    //   router.push('/')
    // }, 1500)
    
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
  <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <div>
        <h1 class="text-center text-3xl font-extrabold text-blue-600">StoreManager</h1>
        <h2 class="mt-6 text-center text-2xl font-bold text-gray-900">Entre na sua conta</h2>
      </div>
      <form class="mt-8 space-y-6" @submit.prevent="submitLogin" novalidate>
        <div class="rounded-md shadow-sm -space-y-px">
          <div>
            <label for="email-address" class="sr-only">Email</label>
            <input 
              id="email-address" 
              name="email" 
              type="email" 
              autocomplete="email" 
              required 
              v-model="email"
              class="form-input rounded-t-md"
              placeholder="Email"
            />
          </div>
          <div>
            <label for="password" class="sr-only">Senha</label>
            <input 
              id="password" 
              name="password" 
              type="password" 
              autocomplete="current-password" 
              required 
              v-model="password"
              class="form-input rounded-b-md"
              placeholder="Senha"
            />
          </div>
        </div>

        <div class="text-red-500 text-sm font-bold p-2 mb-2 bg-red-50 rounded" v-if="error">
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
            {{ loading ? 'Entrando...' : 'Entrar' }}
          </button>
        </div>

        <div class="text-center">
          <p class="text-sm text-gray-600">
            Não tem uma conta?
            <router-link to="/register" class="font-medium text-blue-600 hover:text-blue-500">
              Registre-se
            </router-link>
          </p>
        </div>

        <div class="mt-4 text-center text-sm text-gray-500">
          <p>Credenciais de teste:</p>
          <p>Admin: admin@example.com / password</p>
          <p>User: user@example.com / password</p>
        </div>
      </form>
    </div>
  </div>
</template>
