import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  {
    path: '/',
    name: 'Home',
    component: () => import('../views/Home.vue')
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
    meta: { requiresAuth: true, requiresAdmin: true }
  },
  {
    path: '/categories/:id/edit',
    name: 'EditCategory',
    component: () => import('../views/categories/CategoryForm.vue'),
    meta: { requiresAuth: true, requiresAdmin: true }
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

// Navegação guard para verificar autenticação e permissões
router.beforeEach((to, from, next) => {
  // Implementação futura para verificar autenticação e permissões
  // Por enquanto, permitiremos acesso a todas as rotas
  next()
})

export default router
