<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useToast } from 'vue-toastification'

const router = useRouter()
const toast = useToast()

const email = ref('')
const password = ref('')
const loading = ref(false)
const error = ref('')

const login = async () => {
  // Validação básica
  if (!email.value || !password.value) {
    error.value = 'Email e senha são obrigatórios'
    return
  }

  try {
    loading.value = true
    
    // Simulando login com dados estáticos
    // Em uma implementação real, isso seria uma chamada à API
    await new Promise(resolve => setTimeout(resolve, 1000))
    
    // Simular sucesso de login
    if (email.value === 'admin@example.com' && password.value === 'password') {
      localStorage.setItem('token', 'fake-jwt-token')
      localStorage.setItem('userRole', 'admin')
      toast.success('Login realizado com sucesso!')
      router.push('/')
    } else if (email.value === 'user@example.com' && password.value === 'password') {
      localStorage.setItem('token', 'fake-jwt-token')
      localStorage.setItem('userRole', 'user')
      toast.success('Login realizado com sucesso!')
      router.push('/')
    } else {
      error.value = 'Credenciais inválidas'
      toast.error('Credenciais inválidas')
    }
  } catch (err) {
    error.value = 'Ocorreu um erro ao fazer login'
    toast.error('Ocorreu um erro ao fazer login')
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
        <h2 class="mt-6 text-center text-2xl font-bold text-gray-900">Entre na sua conta</h2>
      </div>
      <form class="mt-8 space-y-6" @submit.prevent="login">
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
