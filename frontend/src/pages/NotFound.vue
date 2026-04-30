<template>
  <body class="min-h-screen bg-gray-50 text-gray-800">
    <main class="min-h-screen px-6 max-[767px]:px-4 max-[479px]:px-3 py-10 max-[767px]:py-6 flex items-center justify-center">
      <section class="max-w-xl w-full bg-white border border-gray-200 rounded-2xl shadow-sm p-8 max-[767px]:p-6 max-[479px]:p-4 text-center">
        <p class="text-sm font-semibold text-blue-600 mb-2">Ошибка 404</p>
        <h1 class="text-3xl max-[991px]:text-2xl max-[479px]:text-xl font-bold text-gray-900 mb-3">Страница не найдена</h1>
        <p class="text-gray-700 mb-6">
          Возможно, адрес введен неверно или страница была перемещена.
        </p>
        <div class="flex flex-wrap justify-center gap-3">
          <router-link
            to="/"
            class="inline-block px-5 py-2 bg-blue-600 text-white font-medium text-xs leading-snug uppercase rounded shadow-sm hover:bg-blue-700 transition duration-150 ease-in-out"
          >
            На главную
          </router-link>
          <router-link
            to="/login"
            class="inline-block px-5 py-2 bg-white text-blue-600 font-medium text-xs leading-snug uppercase rounded border border-gray-300 shadow-sm hover:bg-gray-50 transition duration-150 ease-in-out"
          >
            Войти
          </router-link>
        </div>
      </section>
    </main>
  </body>
</template>

<script setup>
import { onMounted } from 'vue'

onMounted(() => {
  document.title = '404: Страница не найдена — CarShare'
  let meta = document.querySelector('meta[name="description"]')
  if (!meta) {
    meta = document.createElement('meta')
    meta.setAttribute('name', 'description')
    document.head.appendChild(meta)
  }
  meta.setAttribute('content', 'Страница ошибки 404 в CarShare: запрошенный адрес не найден.')
})
</script>

