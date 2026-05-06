import { createRouter, createWebHistory } from 'vue-router'
import Home from '../pages/Home.vue'
import NotFound from '../pages/NotFound.vue'
import ClientHome from '../pages/client/Index.vue'
import Car from '../pages/client/Car.vue'
import Order from '../pages/client/Order.vue'
import AdminHome from '../pages/admin/Index.vue'
import AdminUsers from '../pages/admin/Users.vue'
import AdminCars from '../pages/admin/Cars.vue'
import AdminDocumentRequests from '../pages/admin/DocumentRequests.vue'
import AdminDocumentRequestView from '../pages/admin/DocumentRequestView.vue'
import Register from '../pages/auth/Register.vue'
import Login from '../pages/auth/Login.vue'
import Forgot from '../pages/auth/Forgot.vue'
import Account from '../pages/client/Account.vue'
import Documents from '../pages/client/Documents.vue'
import { api, clearAuthToken, getAuthToken } from '../lib/api'

const routes = [
  {
    path: '/app',
    name: 'client-home',
    component: ClientHome,
    meta: { requiresAuth: true, requiresClient: true },
  },
  {
    path: '/account',
    name: 'account',
    component: Account,
    meta: { requiresAuth: true, requiresClient: true },
  },
  {
    path: '/order/:carId',
    name: 'order',
    component: Order,
    meta: { requiresAuth: true, requiresClient: true },
  },
  {
    path: '/cars/:carId',
    name: 'car-detail',
    component: Car,
  },
  {
    path: '/account/documents',
    name: 'account-documents',
    component: Documents,
    meta: { requiresAuth: true, requiresClient: true },
  },
  {
    path: '/admin',
    name: 'admin-home',
    component: AdminHome,
    meta: { requiresAuth: true, requiresAdmin: true },
  },
  {
    path: '/admin/users',
    name: 'admin-users',
    component: AdminUsers,
    meta: { requiresAuth: true, requiresAdmin: true },
  },
  {
    path: '/admin/cars',
    name: 'admin-cars',
    component: AdminCars,
    meta: { requiresAuth: true, requiresAdmin: true },
  },
  {
    path: '/admin/document-requests',
    name: 'admin-document-requests',
    component: AdminDocumentRequests,
    meta: { requiresAuth: true, requiresAdmin: true },
  },
  {
    path: '/admin/document-requests/:userId',
    name: 'admin-document-request-view',
    component: AdminDocumentRequestView,
    meta: { requiresAuth: true, requiresAdmin: true },
  },
  {
    path: '/',
    name: 'home',
    component: Home,
  },
  {
    path: '/register',
    name: 'register',
    component: Register,
    meta: { guestOnly: true },
  },
  {
    path: '/login',
    name: 'login',
    component: Login,
    meta: { guestOnly: true },
  },
  {
    path: '/forgot',
    name: 'forgot',
    component: Forgot,
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'not-found',
    component: NotFound,
  },
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
})

async function getCurrentUser() {
  const token = getAuthToken()
  if (!token) return null

  try {
    const response = await api.get('/auth/me')
    return response?.data?.data || null
  } catch (error) {
    clearAuthToken()
    return null
  }
}

router.beforeEach(async (to) => {
  const user = await getCurrentUser()
  const isAuth = !!user

  if (to.meta.requiresAuth && !isAuth) {
    return { name: 'login' }
  }

  if (to.meta.guestOnly && isAuth) {
    return user.role === 'admin' ? { name: 'admin-home' } : { name: 'client-home' }
  }

  if (to.meta.requiresAdmin && user?.role !== 'admin') {
    return { name: 'client-home' }
  }

  if (to.meta.requiresClient && user?.role === 'admin') {
    return { name: 'admin-home' }
  }

  return true
})

export default router
