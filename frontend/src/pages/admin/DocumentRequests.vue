<template>
  <body class="min-h-screen bg-gray-50 text-gray-800">
    <script type="text/javascript">
      (function(m,e,t,r,i,k,a){
          m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
          m[i].l=1*new Date();
          for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
          k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)
      })(window, document,'script','https://mc.yandex.ru/metrika/tag.js?id=108989745', 'ym');

      ym(108989745, 'init', {ssr:true, webvisor:true, clickmap:true, ecommerce:"dataLayer", referrer: document.referrer, url: location.href, accurateTrackBounce:true, trackLinks:true});
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/108989745" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <header class="border-b border-solid border-gray-200 bg-white">
      <div class="px-6 max-[767px]:px-4 max-[479px]:px-3 py-4 flex flex-wrap items-center justify-between gap-4 max-w-6xl mx-auto">
        <router-link to="/admin" class="text-2xl font-bold text-gray-900 no-underline">CarShare Admin</router-link>
        <nav class="flex flex-wrap items-center gap-4 text-sm font-semibold">
          <router-link to="/admin/users" class="text-gray-700 hover:text-blue-600">Пользователи</router-link>
          <router-link to="/admin/cars" class="text-gray-700 hover:text-blue-600">Автомобили</router-link>
          <router-link to="/admin/document-requests" class="text-blue-600">Документы</router-link>
          <router-link to="/login" @click="logout" class="text-red-600 hover:text-red-700">Выйти</router-link>
        </nav>
      </div>
    </header>

    <main class="px-6 max-[767px]:px-4 max-[479px]:px-3 py-8 max-[767px]:py-6 lg:py-10 max-w-6xl mx-auto">
      <h1 class="text-3xl max-[991px]:text-2xl max-[479px]:text-xl font-bold text-gray-900 mb-4">Заявки на документы</h1>

      <section class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5">
        <p v-if="isLoading" class="text-sm text-gray-500">Загрузка...</p>
        <p v-else-if="!requests.length" class="text-sm text-gray-700">Нет заявок на проверку.</p>
        <div v-else class="space-y-3">
          <article v-for="item in requests" :key="item.user_id" class="border border-gray-200 rounded-xl p-4 flex justify-between max-[767px]:items-start max-[767px]:flex-col items-center gap-3">
            <div>
              <p class="font-semibold text-gray-900">{{ item.user_name }}</p>
              <p class="text-sm text-gray-600">{{ item.user_email }}</p>
              <p class="text-sm text-gray-600">Документов: {{ item.documents_count }}</p>
            </div>
            <router-link :to="`/admin/document-requests/${item.user_id}`" class="px-4 py-2 bg-blue-600 text-white rounded">
              Открыть
            </router-link>
          </article>
        </div>
      </section>
    </main>
  </body>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { api, performLogout } from '../../lib/api'

const requests = ref([])
const isLoading = ref(false)

async function loadRequests() {
  isLoading.value = true
  try {
    const response = await api.get('/admin/document-requests')
    requests.value = response?.data?.data || []
  } finally {
    isLoading.value = false
  }
}

async function logout() {
  await performLogout()
}

onMounted(() => {
  document.title = 'Админ: Заявки на документы — CarShare'
  let meta = document.querySelector('meta[name="description"]')
  if (!meta) {
    meta = document.createElement('meta')
    meta.setAttribute('name', 'description')
    document.head.appendChild(meta)
  }
  meta.setAttribute('content', 'Очередь заявок на проверку документов пользователей в админ-панели CarShare.')

  loadRequests()
})
</script>

