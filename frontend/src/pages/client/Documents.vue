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
        <router-link to="/" class="text-2xl font-bold text-gray-900 no-underline">
          CarShare
        </router-link>
        <nav class="flex flex-wrap items-center gap-4 text-sm font-semibold" aria-label="Навигация клиента">
          <router-link to="/app" class="text-gray-700 hover:text-blue-600 transition duration-200 ease-in-out">
            Выбор авто
          </router-link>
          <router-link to="/account" class="text-gray-700 hover:text-blue-600 transition duration-200 ease-in-out">
            Личный кабинет
          </router-link>
          <router-link to="/login" @click="logout" class="text-red-600 hover:text-red-700 transition duration-200 ease-in-out">
            Выйти
          </router-link>
        </nav>
      </div>
    </header>

    <main class="px-6 max-[767px]:px-4 max-[479px]:px-3 py-8 max-[767px]:py-6 lg:py-10">
      <section class="max-w-6xl mx-auto mb-6">
        <div class="flex flex-wrap items-start justify-between gap-4">
          <div>
            <h1 class="text-3xl lg:text-4xl max-[991px]:text-2xl max-[479px]:text-xl font-bold text-gray-900 pb-2">Загрузка документов</h1>
            <p class="text-gray-700 text-lg max-[767px]:text-base leading-relaxed">
              Добавьте фото документов для подтверждения аккаунта.
            </p>
          </div>
          <router-link
            to="/account"
            class="inline-block px-5 py-2 bg-white text-blue-600 font-medium text-xs leading-snug uppercase rounded border border-solid border-gray-300 shadow-sm hover:bg-gray-50 transition duration-150 ease-in-out"
          >
            Назад
          </router-link>
        </div>
      </section>

      <section class="max-w-6xl mx-auto grid lg:grid-cols-3 gap-6 max-[767px]:gap-4">
        <section class="lg:col-span-2 bg-white border border-solid border-gray-200 rounded-2xl shadow-sm p-5">
          <h2 class="text-xl font-bold text-gray-900 mb-4">Файлы</h2>

          <div class="grid md:grid-cols-2 gap-4">
            <div class="border border-dashed border-gray-300 rounded-2xl p-4 bg-gray-50">
              <p class="font-semibold text-gray-900">Паспорт</p>
              <p class="text-xs text-gray-600 pt-1">Фото разворота с данными. Форматы: JPG/PNG/WebP.</p>
              <input
                type="file"
                accept="image/*"
                class="mt-3 block w-full text-sm text-gray-700 file:mr-3 file:py-2 file:px-3 file:rounded file:border-0 file:bg-blue-600 file:text-white hover:file:bg-blue-700"
                @change="onPick($event, 'passport')"
              />
              <div v-if="previews.passport" class="mt-3">
                <img :src="previews.passport" alt="Предпросмотр паспорта" class="w-full h-40 object-cover rounded-xl border border-gray-200" />
              </div>
            </div>

            <div class="border border-dashed border-gray-300 rounded-2xl p-4 bg-gray-50">
              <p class="font-semibold text-gray-900">Водительское удостоверение</p>
              <p class="text-xs text-gray-600 pt-1">Фото лицевой стороны. Форматы: JPG/PNG/WebP.</p>
              <input
                type="file"
                accept="image/*"
                class="mt-3 block w-full text-sm text-gray-700 file:mr-3 file:py-2 file:px-3 file:rounded file:border-0 file:bg-blue-600 file:text-white hover:file:bg-blue-700"
                @change="onPick($event, 'license')"
              />
              <div v-if="previews.license" class="mt-3">
                <img :src="previews.license" alt="Предпросмотр прав" class="w-full h-40 object-cover rounded-xl border border-gray-200" />
              </div>
            </div>

            <div class="border border-dashed border-gray-300 rounded-2xl p-4 bg-gray-50 md:col-span-2">
              <p class="font-semibold text-gray-900">Селфи с документом</p>
              <p class="text-xs text-gray-600 pt-1">Селфи, где видно лицо и документ в руках.</p>
              <input
                type="file"
                accept="image/*"
                class="mt-3 block w-full text-sm text-gray-700 file:mr-3 file:py-2 file:px-3 file:rounded file:border-0 file:bg-blue-600 file:text-white hover:file:bg-blue-700"
                @change="onPick($event, 'selfie')"
              />
              <div v-if="previews.selfie" class="mt-3">
                <img :src="previews.selfie" alt="Предпросмотр селфи" class="w-full h-56 object-cover rounded-xl border border-gray-200" />
              </div>
            </div>
          </div>
        </section>

        <aside class="bg-white border border-solid border-gray-200 rounded-2xl shadow-sm p-5">
          <h2 class="text-xl font-bold text-gray-900 mb-4">Проверка</h2>
          <ul class="list-disc list-inside text-sm text-gray-700 space-y-2">
            <li>Фото должно быть чётким, без бликов.</li>
            <li>Данные на документе должны читаться.</li>
            <li>Загрузите актуальные документы, чтобы избежать блокировки.</li>
          </ul>

          <button
            type="button"
            @click="submitDocuments"
            class="mt-5 w-full inline-block px-7 py-3 bg-blue-600 text-white font-medium text-sm leading-snug uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out"
          >
            Отправить на проверку
          </button>
          <p v-if="statusMessage" class="text-sm text-green-700 mt-3">{{ statusMessage }}</p>
          <p v-if="errorMessage" class="text-sm text-red-600 mt-3">{{ errorMessage }}</p>

        </aside>
      </section>
    </main>
  </body>
</template>

<script setup>
import { onBeforeUnmount, onMounted, reactive, ref } from 'vue'
import { api, performLogout } from '../../lib/api'

const previews = reactive({
  passport: '',
  license: '',
  selfie: '',
})

const objectUrls = reactive({
  passport: '',
  license: '',
  selfie: '',
})
const selectedFiles = reactive({
  passport: null,
  license: null,
  selfie: null,
})
const statusMessage = ref('')
const errorMessage = ref('')

async function logout() {
  await performLogout()
}

function setPreview(key, file) {
  if (objectUrls[key]) URL.revokeObjectURL(objectUrls[key])
  objectUrls[key] = file ? URL.createObjectURL(file) : ''
  previews[key] = objectUrls[key]
}

function onPick(event, key) {
  const file = event?.target?.files?.[0]
  if (!file) return
  selectedFiles[key] = file
  setPreview(key, file)
}

async function uploadOne(type, file) {
  const formData = new FormData()
  formData.append('type', type)
  formData.append('file', file)
  await api.post('/documents', formData, {
    headers: { 'Content-Type': 'multipart/form-data' },
  })
}

async function submitDocuments() {
  statusMessage.value = ''
  errorMessage.value = ''

  try {
    const uploads = []
    for (const type of ['passport', 'license', 'selfie']) {
      if (selectedFiles[type]) {
        uploads.push(uploadOne(type, selectedFiles[type]))
      }
    }
    if (uploads.length === 0) {
      errorMessage.value = 'Выберите хотя бы один файл.'
      return
    }
    await Promise.all(uploads)
    statusMessage.value = 'Документы отправлены на проверку.'
  } catch (error) {
    errorMessage.value = error?.response?.data?.message || 'Не удалось отправить документы.'
  }
}

onBeforeUnmount(() => {
  for (const key of Object.keys(objectUrls)) {
    if (objectUrls[key]) URL.revokeObjectURL(objectUrls[key])
  }
})

onMounted(() => {
  document.title = 'Загрузка документов — CarShare'
  let meta = document.querySelector('meta[name="description"]')
  if (!meta) {
    meta = document.createElement('meta')
    meta.setAttribute('name', 'description')
    document.head.appendChild(meta)
  }
  meta.setAttribute('content', 'Страница загрузки паспорта, водительского удостоверения и селфи для верификации в CarShare.')
})
</script>

