import { createRouter, createWebHistory } from 'vue-router'
import authService from '../services/authService'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: () => import('../views/Home.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('../views/auth/Login.vue')
  },
  {
    path: '/register',
    name: 'Register',
    component: () => import('../views/auth/Register.vue')
  },
  {
    path: '/products',
    name: 'Products',
    component: () => import('../views/products/ProductList.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/products/create',
    name: 'CreateProduct',
    component: () => import('../views/products/ProductForm.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/products/:id/edit',
    name: 'EditProduct',
    component: () => import('../views/products/ProductForm.vue'),
    meta: { requiresAuth: true, requiresAdmin: true }
  },
  {
    path: '/categories',
    name: 'Categories',
    component: () => import('../views/categories/CategoryList.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/categories/create',
    name: 'CreateCategory',
    component: () => import('../views/categories/CategoryForm.vue'),
    meta: { requiresAuth: true }
  },
  {
    path: '/categories/:id/edit',
    name: 'EditCategory',
    component: () => import('../views/categories/CategoryForm.vue'),
    meta: { requiresAuth: true, requiresAdmin: true }
  },
  {
    path: '/users',
    name: 'Users',
    component: () => import('../views/users/UserList.vue'),
    meta: { requiresAuth: true, requiresAdmin: true }
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

// Navegação guard para verificar autenticação e permissões
router.beforeEach((to, from, next) => {
  const requiresAuth = to.matched.some(record => record.meta.requiresAuth)
  const requiresAdmin = to.matched.some(record => record.meta.requiresAdmin)
  const isAuthenticated = authService.isAuthenticated()
  const isAdmin = authService.isAdmin()

  // Se a rota requer autenticação e o usuário não está autenticado
  if (requiresAuth && !isAuthenticated) {
    next({ name: 'Login' })
  }
  // Se a rota requer role de admin e o usuário não é admin
  else if (requiresAdmin && !isAdmin) {
    next({ name: 'Home' }) // Redireciona para a home se não tiver permissão
  }
  // Se o usuário já está autenticado e tenta acessar login/register
  else if (isAuthenticated && (to.name === 'Login' || to.name === 'Register')) {
    next({ name: 'Home' })
  }
  // Em todos os outros casos, permite o acesso
  else {
    next()
  }
})

export default router
