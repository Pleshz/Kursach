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
        <router-link to="/admin" class="text-2xl font-bold text-gray-900 no-underline">
          CarShare Admin
        </router-link>
        <nav class="flex flex-wrap items-center gap-4 text-sm font-semibold">
          <router-link to="/admin/users" class="text-gray-700 hover:text-blue-600">Пользователи</router-link>
          <router-link to="/admin/cars" class="text-gray-700 hover:text-blue-600">Автомобили</router-link>
          <router-link to="/admin/document-requests" class="text-gray-700 hover:text-blue-600">Документы</router-link>
          <router-link to="/login" @click="logout" class="text-red-600 hover:text-red-700">Выйти</router-link>
        </nav>
      </div>
    </header>

    <main class="px-6 max-[767px]:px-4 max-[479px]:px-3 py-8 max-[767px]:py-6 lg:py-10">
      <section class="max-w-6xl mx-auto">
        <h1 class="text-3xl lg:text-4xl max-[991px]:text-2xl max-[479px]:text-xl font-bold text-gray-900 pb-3">Админ-панель</h1>
        <p class="text-gray-700 text-lg max-[767px]:text-base leading-relaxed pb-6">
          Управляйте пользователями, автомобилями и заявками на подтверждение документов.
        </p>

        <div class="grid md:grid-cols-3 gap-6 mb-6">
          <router-link to="/admin/users" class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm hover:border-blue-500">
            <h2 class="text-xl font-bold text-gray-900 mb-2">Пользователи</h2>
            <p class="text-sm text-gray-600">Создание, редактирование и удаление пользователей.</p>
          </router-link>
          <router-link to="/admin/cars" class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm hover:border-blue-500">
            <h2 class="text-xl font-bold text-gray-900 mb-2">Автомобили</h2>
            <p class="text-sm text-gray-600">CRUD-операции с автопарком и тарифами.</p>
          </router-link>
          <router-link to="/admin/document-requests" class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm hover:border-blue-500">
            <h2 class="text-xl font-bold text-gray-900 mb-2">Проверка документов</h2>
            <p class="text-sm text-gray-600">Просмотр заявок и принятие решения по документам.</p>
          </router-link>
        </div>

        <section class="bg-white border border-gray-200 rounded-2xl p-5 shadow-sm max-w-xl">
          <h2 class="text-xl font-bold text-gray-900 mb-2">Отчеты</h2>
          <p class="text-sm text-gray-600 mb-4">
            Скачайте Excel-отчет с данными по пользователям, автомобилям, заказам и документам.
          </p>
          <button
            type="button"
            :disabled="isDownloading"
            @click="downloadExcelReport"
            class="inline-block px-5 py-2 bg-blue-600 text-white font-medium text-xs leading-snug uppercase rounded shadow-sm hover:bg-blue-700 transition duration-150 ease-in-out disabled:bg-gray-400 disabled:cursor-not-allowed"
          >
            {{ isDownloading ? 'Формируем...' : 'Скачать Excel-отчет' }}
          </button>
          <p v-if="errorMessage" class="text-sm text-red-600 mt-3">{{ errorMessage }}</p>
        </section>
      </section>
    </main>
  </body>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { api, performLogout } from '../../lib/api'

const isDownloading = ref(false)
const errorMessage = ref('')

async function downloadExcelReport() {
  errorMessage.value = ''
  isDownloading.value = true
  try {
    const response = await api.get('/admin/reports/excel', { responseType: 'blob' })
    const fileBlob = new Blob([response.data], { type: 'application/vnd.ms-excel' })
    const fileUrl = URL.createObjectURL(fileBlob)
    const link = document.createElement('a')
    link.href = fileUrl
    link.download = `carshare-report-${new Date().toISOString().slice(0, 10)}.xls`
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
    URL.revokeObjectURL(fileUrl)
  } catch (error) {
    errorMessage.value = 'Не удалось скачать отчет.'
  } finally {
    isDownloading.value = false
  }
}

async function logout() {
  await performLogout()
}

onMounted(() => {
  document.title = 'Админ-панель CarShare'
  let meta = document.querySelector('meta[name="description"]')
  if (!meta) {
    meta = document.createElement('meta')
    meta.setAttribute('name', 'description')
    document.head.appendChild(meta)
  }
  meta.setAttribute('content', 'Главная страница админ-панели CarShare с разделами управления и выгрузкой отчетов.')
})
</script>

