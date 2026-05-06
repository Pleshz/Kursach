<template>
  <body class="min-h-screen bg-gray-50 text-gray-800">
    <header class="border-b border-solid border-gray-200 bg-white">
      <div class="px-6 max-[767px]:px-4 max-[479px]:px-3 py-4 flex flex-wrap items-center justify-between gap-4 max-w-6xl mx-auto">
        <router-link to="/admin" class="text-2xl font-bold text-gray-900 no-underline">CarShare Admin</router-link>
        <nav class="flex flex-wrap items-center gap-4 text-sm font-semibold">
          <router-link to="/admin/document-requests" class="text-blue-600">К заявкам</router-link>
          <router-link to="/login" @click="logout" class="text-red-600 hover:text-red-700">Выйти</router-link>
        </nav>
      </div>
    </header>

    <main class="px-6 max-[767px]:px-4 max-[479px]:px-3 py-8 max-[767px]:py-6 lg:py-10 max-w-6xl mx-auto">
      <h1 class="text-3xl max-[991px]:text-2xl max-[479px]:text-xl font-bold text-gray-900 mb-4">Проверка документов</h1>

      <section class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5">
        <p v-if="isLoading" class="text-sm text-gray-500">Загрузка...</p>
        <p v-else-if="!documents.length" class="text-sm text-gray-700">По пользователю нет документов.</p>
        <div v-else class="space-y-4">
          <article v-for="doc in documents" :key="doc.id" class="border border-gray-200 rounded-xl p-4">
            <div class="flex justify-between gap-4 flex-wrap">
              <div>
                <p class="font-semibold text-gray-900">{{ mapType(doc.type) }}</p>
                <p class="text-sm text-gray-600">{{ doc.user_name }} · {{ doc.user_email }}</p>
                <p class="text-sm text-gray-600">Статус: {{ mapStatus(doc.status) }}</p>
              </div>
              <a :href="doc.url" target="_blank" class="px-4 py-2 bg-gray-200 rounded">Открыть файл</a>
            </div>
            <div class="mt-3 flex max-[479px]:flex-col gap-2" v-if="doc.status === 'uploaded'">
              <button class="px-4 py-2 bg-green-600 text-white rounded" @click="decide(doc.id, 'verified')">Одобрить</button>
              <button class="px-4 py-2 bg-red-600 text-white rounded" @click="decide(doc.id, 'rejected')">Отклонить</button>
            </div>
          </article>
        </div>
      </section>
    </main>
  </body>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'
import { api, performLogout } from '../../lib/api'

const route = useRoute()
const isLoading = ref(false)
const documents = ref([])

function mapType(type) {
  if (type === 'passport') return 'Паспорт'
  if (type === 'license') return 'Водительское удостоверение'
  if (type === 'selfie') return 'Селфи с документом'
  return type || 'Документ'
}

function mapStatus(status) {
  if (status === 'uploaded') return 'На проверке'
  if (status === 'verified') return 'Одобрен'
  if (status === 'rejected') return 'Отклонен'
  return status || '—'
}

async function loadDocuments() {
  isLoading.value = true
  try {
    const response = await api.get(`/admin/document-requests/${route.params.userId}`)
    documents.value = response?.data?.data || []
  } finally {
    isLoading.value = false
  }
}

async function decide(documentId, status) {
  await api.patch(`/admin/documents/${documentId}/decision`, { status })
  await loadDocuments()
}

async function logout() {
  await performLogout()
}

onMounted(() => {
  document.title = 'Админ: Проверка документов пользователя — CarShare'
  let meta = document.querySelector('meta[name="description"]')
  if (!meta) {
    meta = document.createElement('meta')
    meta.setAttribute('name', 'description')
    document.head.appendChild(meta)
  }
  meta.setAttribute('content', 'Карточка проверки документов пользователя с действиями одобрения или отклонения.')

  loadDocuments()
})
</script>

