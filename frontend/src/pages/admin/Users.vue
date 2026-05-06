<template>
  <body class="min-h-screen bg-gray-50 text-gray-800">
    <header class="border-b border-solid border-gray-200 bg-white">
      <div class="px-6 max-[767px]:px-4 max-[479px]:px-3 py-4 flex flex-wrap items-center justify-between gap-4 max-w-6xl mx-auto">
        <router-link to="/admin" class="text-2xl font-bold text-gray-900 no-underline">CarShare Admin</router-link>
        <nav class="flex flex-wrap items-center gap-4 text-sm font-semibold">
          <router-link to="/admin/users" class="text-blue-600">Пользователи</router-link>
          <router-link to="/admin/cars" class="text-gray-700 hover:text-blue-600">Автомобили</router-link>
          <router-link to="/admin/document-requests" class="text-gray-700 hover:text-blue-600">Документы</router-link>
          <router-link to="/login" @click="logout" class="text-red-600 hover:text-red-700">Выйти</router-link>
        </nav>
      </div>
    </header>

    <main class="px-6 max-[767px]:px-4 max-[479px]:px-3 py-8 max-[767px]:py-6 lg:py-10 max-w-6xl mx-auto">
      <h1 class="text-3xl max-[991px]:text-2xl max-[479px]:text-xl font-bold text-gray-900 mb-4">Пользователи</h1>

      <section class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-3">{{ editingUserId ? 'Редактировать пользователя' : 'Новый пользователь' }}</h2>
        <form class="grid md:grid-cols-2 gap-3" @submit.prevent="saveUser">
          <input v-model="form.name" class="px-3 py-2 border border-gray-300 rounded" placeholder="Имя" />
          <input v-model="form.email" type="email" class="px-3 py-2 border border-gray-300 rounded" placeholder="Email" />
          <input v-model="form.password" type="password" class="px-3 py-2 border border-gray-300 rounded" placeholder="Пароль (минимум 8)" />
          <select v-model="form.role" class="px-3 py-2 border border-gray-300 rounded">
            <option value="client">client</option>
            <option value="admin">admin</option>
          </select>
          <div class="md:col-span-2 flex max-[479px]:flex-col gap-2">
            <button class="px-4 py-2 bg-blue-600 text-white rounded">{{ editingUserId ? 'Сохранить' : 'Создать' }}</button>
            <button v-if="editingUserId" type="button" class="px-4 py-2 bg-gray-200 rounded" @click="resetForm">Отмена</button>
          </div>
        </form>
        <p v-if="errorMessage" class="text-sm text-red-600 mt-3">{{ errorMessage }}</p>
      </section>

      <section class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5">
        <h2 class="text-xl font-bold text-gray-900 mb-3">Список</h2>
        <p v-if="isLoading" class="text-sm text-gray-500">Загрузка...</p>
        <div v-else class="overflow-x-auto">
          <table class="w-full text-sm min-w-[700px]">
          <thead>
            <tr class="text-left border-b border-gray-200">
              <th class="py-2">ID</th>
              <th class="py-2">Имя</th>
              <th class="py-2">Email</th>
              <th class="py-2">Роль</th>
              <th class="py-2">Действия</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="user in users" :key="user.id" class="border-b border-gray-100">
              <td class="py-2">{{ user.id }}</td>
              <td class="py-2">{{ user.name }}</td>
              <td class="py-2">{{ user.email }}</td>
              <td class="py-2">{{ user.role }}</td>
              <td class="py-2">
                <div class="flex gap-2">
                <button class="px-3 py-1 bg-gray-200 rounded" @click="startEdit(user)">Редактировать</button>
                <button class="px-3 py-1 bg-red-600 text-white rounded" @click="removeUser(user.id)">Удалить</button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
        </div>
      </section>
    </main>
  </body>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue'
import { api, performLogout } from '../../lib/api'

const users = ref([])
const isLoading = ref(false)
const errorMessage = ref('')
const editingUserId = ref(null)

const form = reactive({
  name: '',
  email: '',
  password: '',
  role: 'client',
})

function resetForm() {
  editingUserId.value = null
  form.name = ''
  form.email = ''
  form.password = ''
  form.role = 'client'
  errorMessage.value = ''
}

async function loadUsers() {
  isLoading.value = true
  try {
    const response = await api.get('/admin/users')
    users.value = response?.data?.data || []
  } finally {
    isLoading.value = false
  }
}

function startEdit(user) {
  editingUserId.value = user.id
  form.name = user.name || ''
  form.email = user.email || ''
  form.password = ''
  form.role = user.role || 'client'
}

async function saveUser() {
  errorMessage.value = ''
  try {
    const payload = {
      name: form.name,
      email: form.email,
      role: form.role,
    }
    if (form.password) payload.password = form.password

    if (editingUserId.value) {
      await api.put(`/admin/users/${editingUserId.value}`, payload)
    } else {
      if (!form.password) {
        errorMessage.value = 'Для нового пользователя пароль обязателен.'
        return
      }
      await api.post('/admin/users', payload)
    }
    resetForm()
    await loadUsers()
  } catch (error) {
    errorMessage.value = error?.response?.data?.message || 'Не удалось сохранить пользователя.'
  }
}

async function removeUser(id) {
  if (!confirm('Удалить пользователя?')) return
  try {
    await api.delete(`/admin/users/${id}`)
    await loadUsers()
  } catch (error) {
    errorMessage.value = error?.response?.data?.message || 'Не удалось удалить пользователя.'
  }
}

async function logout() {
  await performLogout()
}

onMounted(() => {
  document.title = 'Админ: Пользователи — CarShare'
  let meta = document.querySelector('meta[name="description"]')
  if (!meta) {
    meta = document.createElement('meta')
    meta.setAttribute('name', 'description')
    document.head.appendChild(meta)
  }
  meta.setAttribute('content', 'Раздел админ-панели CarShare для создания, редактирования и удаления пользователей.')

  loadUsers()
})
</script>

