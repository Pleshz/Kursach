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
          <router-link to="/account" class="text-blue-600 hover:text-blue-700 transition duration-200 ease-in-out">
            Личный кабинет
          </router-link>
          <router-link to="/login" @click="logout" class="text-red-600 hover:text-red-700 transition duration-200 ease-in-out">
            Выйти
          </router-link>
        </nav>
      </div>
    </header>

    <main class="px-6 max-[767px]:px-4 max-[479px]:px-3 py-8 max-[767px]:py-6 lg:py-10">
      <!-- Заголовок -->
      <section class="max-w-6xl mx-auto mb-10">
        <h1 class="text-3xl lg:text-4xl max-[991px]:text-2xl max-[479px]:text-xl font-bold text-gray-900 pb-3">Личный кабинет</h1>
        <p class="text-gray-700 text-lg max-[767px]:text-base leading-relaxed pb-4">
          Здесь собрана основная информация о вашем профиле и статусе аккаунта.
        </p>
      </section>

      <section class="max-w-6xl mx-auto mb-6">
        <p v-if="errorMessage" class="text-sm text-red-600">{{ errorMessage }}</p>
      </section>

      <section class="max-w-6xl mx-auto mb-10">
        <section class="bg-white border border-solid border-gray-200 rounded-2xl p-5 shadow-sm">
          <h2 class="text-xl font-bold text-gray-900 mb-4">Профиль</h2>
          <p v-if="isLoading" class="text-sm text-gray-500">Загрузка профиля...</p>
          <dl v-else class="grid sm:grid-cols-2 gap-4 text-sm text-gray-700">
            <div>
              <dt class="font-semibold text-gray-900 mb-1">Имя</dt>
              <dd>{{ profile.name }}</dd>
            </div>
            <div>
              <dt class="font-semibold text-gray-900 mb-1">Email</dt>
              <dd>{{ profile.email }}</dd>
            </div>
            <div>
              <dt class="font-semibold text-gray-900 mb-1">Дата регистрации</dt>
              <dd>{{ profile.registeredAt }}</dd>
            </div>
          </dl>
        </section>
      </section>

      <section class="max-w-6xl mx-auto mb-10 grid md:grid-cols-2 gap-6">
        <section class="bg-white border border-solid border-gray-200 rounded-2xl p-5 shadow-sm">
          <h2 class="text-xl font-bold text-gray-900 mb-4">Документы</h2>
          <ul class="space-y-3 text-sm text-gray-700">
            <li class="flex justify-between max-[479px]:flex-col gap-1">
              <span>Паспорт</span>
              <span class="text-gray-500">{{ documentStatuses.passport }}</span>
            </li>
            <li class="flex justify-between max-[479px]:flex-col gap-1">
              <span>Водительское удостоверение</span>
              <span class="text-gray-500">{{ documentStatuses.license }}</span>
            </li>
          </ul>

          <router-link
            to="/account/documents"
            class="mt-4 inline-block px-4 py-2 bg-blue-600 text-white font-medium text-xs leading-snug uppercase rounded shadow-sm hover:bg-blue-700 focus:bg-blue-700 focus:outline-none transition duration-150 ease-in-out"
          >
            Загрузить документы
          </router-link>
        </section>
      </section>
    </main>
  </body>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue'
import { api, performLogout } from '../../lib/api'

const isLoading = ref(true)
const errorMessage = ref('')

const profile = reactive({
  name: '—',
  email: '—',
  registeredAt: '—',
  updatedAt: '—',
  emailVerified: 'Нет',
})
const documentStatuses = reactive({
  passport: 'Не загружено',
  license: 'Не загружено',
})

async function logout() {
  await performLogout()
}

function formatDate(value) {
  if (!value) return '—'
  const date = new Date(value)
  if (Number.isNaN(date.getTime())) return '—'
  return date.toLocaleDateString('ru-RU')
}

function mapRoleLabel(role) {
  if (role === 'admin') return 'Администратор'
  if (role === 'client') return 'Клиент'
  return role || '—'
}

function mapDocumentStatus(status) {
  if (status === 'uploaded') return 'В обработке'
  if (status === 'verified') return 'Подтверждено'
  if (status === 'rejected') return 'Отклонено'
  return 'Не загружено'
}

async function loadProfile() {
  isLoading.value = true
  errorMessage.value = ''
  try {
    const response = await api.get('/auth/me')
    const user = response?.data?.data || {}
    profile.name = user.name || '—'
    profile.email = user.email || '—'
    profile.registeredAt = formatDate(user.created_at)
    profile.updatedAt = formatDate(user.updated_at)
    profile.emailVerified = user.email_verified_at ? 'Да' : 'Нет'
  } catch (error) {
    errorMessage.value = error?.response?.data?.message || 'Не удалось загрузить профиль.'
  } finally {
    isLoading.value = false
  }
}

async function loadDocumentStatuses() {
  try {
    const response = await api.get('/documents')
    const docs = response?.data?.data || []
    const byType = {
      passport: docs.find((doc) => doc.type === 'passport'),
      license: docs.find((doc) => doc.type === 'license'),
    }

    documentStatuses.passport = byType.passport
      ? mapDocumentStatus(byType.passport.status)
      : 'Не загружено'
    documentStatuses.license = byType.license
      ? mapDocumentStatus(byType.license.status)
      : 'Не загружено'
  } catch (error) {
    documentStatuses.passport = 'Не загружено'
    documentStatuses.license = 'Не загружено'
  }
}

onMounted(() => {
  document.title = 'Личный кабинет — CarShare'
  let meta = document.querySelector('meta[name="description"]')
  if (!meta) {
    meta = document.createElement('meta')
    meta.setAttribute('name', 'description')
    document.head.appendChild(meta)
  }
  meta.setAttribute('content', 'Личный кабинет пользователя CarShare: профиль и статусы загруженных документов.')

  loadProfile()
  loadDocumentStatuses()
})
</script>