<template>
  <body class="min-h-screen bg-gray-50 text-gray-800">
    <header class="border-b border-solid border-gray-200 bg-white">
      <div class="px-6 max-[767px]:px-4 max-[479px]:px-3 py-4 flex flex-wrap items-center justify-between gap-4 max-w-6xl mx-auto">
        <router-link to="/admin" class="text-2xl font-bold text-gray-900 no-underline">CarShare Admin</router-link>
        <nav class="flex flex-wrap items-center gap-4 text-sm font-semibold">
          <router-link to="/admin/users" class="text-gray-700 hover:text-blue-600">Пользователи</router-link>
          <router-link to="/admin/cars" class="text-blue-600">Автомобили</router-link>
          <router-link to="/admin/document-requests" class="text-gray-700 hover:text-blue-600">Документы</router-link>
          <router-link to="/login" @click="logout" class="text-red-600 hover:text-red-700">Выйти</router-link>
        </nav>
      </div>
    </header>

    <main class="px-6 max-[767px]:px-4 max-[479px]:px-3 py-8 max-[767px]:py-6 lg:py-10 max-w-6xl mx-auto">
      <h1 class="text-3xl max-[991px]:text-2xl max-[479px]:text-xl font-bold text-gray-900 mb-4">Автомобили</h1>

      <section class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5 mb-6">
        <h2 class="text-xl font-bold text-gray-900 mb-3">{{ editingCarId ? 'Редактировать авто' : 'Новое авто' }}</h2>
        <form class="grid md:grid-cols-2 gap-3" @submit.prevent="saveCar">
          <input v-model="form.name" class="px-3 py-2 border border-gray-300 rounded" placeholder="Название" />
          <input v-model="form.slug" class="px-3 py-2 border border-gray-300 rounded" placeholder="Slug" />
          <input v-model="form.segment" class="px-3 py-2 border border-gray-300 rounded" placeholder="Сегмент" />
          <input v-model="form.transmission" class="px-3 py-2 border border-gray-300 rounded" placeholder="Трансмиссия" />
          <input v-model="form.fuel" class="px-3 py-2 border border-gray-300 rounded" placeholder="Топливо" />
          <input v-model.number="form.range_km" type="number" class="px-3 py-2 border border-gray-300 rounded" placeholder="Запас хода" />
          <input v-model.number="form.rate_minute" type="number" class="px-3 py-2 border border-gray-300 rounded" placeholder="Цена / мин" />
          <input v-model.number="form.rate_hour" type="number" class="px-3 py-2 border border-gray-300 rounded" placeholder="Цена / час" />
          <input v-model.number="form.rate_day" type="number" class="px-3 py-2 border border-gray-300 rounded" placeholder="Цена / день" />
          <input v-model="form.image_url" class="px-3 py-2 border border-gray-300 rounded" placeholder="URL изображения" />
          <input v-model.number="form.latitude" type="number" step="0.000001" class="px-3 py-2 border border-gray-300 rounded" placeholder="Latitude" />
          <input v-model.number="form.longitude" type="number" step="0.000001" class="px-3 py-2 border border-gray-300 rounded" placeholder="Longitude" />
          <textarea v-model="form.description" class="md:col-span-2 px-3 py-2 border border-gray-300 rounded" placeholder="Описание"></textarea>
          <label class="md:col-span-2 flex items-center gap-2 text-sm">
            <input v-model="form.is_active" type="checkbox" />
            Активен
          </label>
          <div class="md:col-span-2 flex max-[479px]:flex-col gap-2">
            <button class="px-4 py-2 bg-blue-600 text-white rounded">{{ editingCarId ? 'Сохранить' : 'Создать' }}</button>
            <button v-if="editingCarId" type="button" class="px-4 py-2 bg-gray-200 rounded" @click="resetForm">Отмена</button>
          </div>
        </form>
        <p v-if="errorMessage" class="text-sm text-red-600 mt-3">{{ errorMessage }}</p>
      </section>

      <section class="bg-white border border-gray-200 rounded-2xl shadow-sm p-5">
        <h2 class="text-xl font-bold text-gray-900 mb-3">Список</h2>
        <p v-if="isLoading" class="text-sm text-gray-500">Загрузка...</p>
        <div v-else class="overflow-x-auto">
          <table class="w-full text-sm min-w-[760px]">
          <thead>
            <tr class="text-left border-b border-gray-200">
              <th class="py-2">ID</th>
              <th class="py-2">Название</th>
              <th class="py-2">Сегмент</th>
              <th class="py-2">Тарифы</th>
              <th class="py-2">Статус</th>
              <th class="py-2">Действия</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="car in cars" :key="car.id" class="border-b border-gray-100">
              <td class="py-2">{{ car.id }}</td>
              <td class="py-2">{{ car.name }}</td>
              <td class="py-2">{{ car.segment }}</td>
              <td class="py-2">{{ car.rate_minute }}/{{ car.rate_hour }}/{{ car.rate_day }}</td>
              <td class="py-2">{{ car.is_active ? 'Активен' : 'Выключен' }}</td>
              <td class="py-2">
                <div class="flex gap-2">
                <button class="px-3 py-1 bg-gray-200 rounded" @click="startEdit(car)">Редактировать</button>
                <button class="px-3 py-1 bg-red-600 text-white rounded" @click="removeCar(car.id)">Удалить</button>
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

const cars = ref([])
const isLoading = ref(false)
const errorMessage = ref('')
const editingCarId = ref(null)

const form = reactive({
  slug: '',
  name: '',
  segment: '',
  transmission: '',
  fuel: '',
  description: '',
  image_url: '',
  latitude: null,
  longitude: null,
  rate_minute: 0,
  rate_hour: 0,
  rate_day: 0,
  range_km: 0,
  is_active: true,
})

function resetForm() {
  editingCarId.value = null
  form.slug = ''
  form.name = ''
  form.segment = ''
  form.transmission = ''
  form.fuel = ''
  form.description = ''
  form.image_url = ''
  form.latitude = null
  form.longitude = null
  form.rate_minute = 0
  form.rate_hour = 0
  form.rate_day = 0
  form.range_km = 0
  form.is_active = true
  errorMessage.value = ''
}

async function loadCars() {
  isLoading.value = true
  try {
    const response = await api.get('/admin/cars')
    cars.value = response?.data?.data || []
  } finally {
    isLoading.value = false
  }
}

function payloadFromForm() {
  return {
    slug: form.slug || null,
    name: form.name,
    segment: form.segment,
    transmission: form.transmission,
    fuel: form.fuel,
    description: form.description || null,
    image_url: form.image_url || null,
    latitude: form.latitude ?? null,
    longitude: form.longitude ?? null,
    rate_minute: Number(form.rate_minute || 0),
    rate_hour: Number(form.rate_hour || 0),
    rate_day: Number(form.rate_day || 0),
    range_km: Number(form.range_km || 0),
    is_active: !!form.is_active,
  }
}

function startEdit(car) {
  editingCarId.value = car.id
  form.slug = car.slug || ''
  form.name = car.name || ''
  form.segment = car.segment || ''
  form.transmission = car.transmission || ''
  form.fuel = car.fuel || ''
  form.description = car.description || ''
  form.image_url = car.image_url || ''
  form.latitude = car.latitude
  form.longitude = car.longitude
  form.rate_minute = Number(car.rate_minute || 0)
  form.rate_hour = Number(car.rate_hour || 0)
  form.rate_day = Number(car.rate_day || 0)
  form.range_km = Number(car.range_km || 0)
  form.is_active = !!car.is_active
}

async function saveCar() {
  errorMessage.value = ''
  try {
    const payload = payloadFromForm()
    if (editingCarId.value) {
      await api.put(`/admin/cars/${editingCarId.value}`, payload)
    } else {
      await api.post('/admin/cars', payload)
    }
    resetForm()
    await loadCars()
  } catch (error) {
    errorMessage.value = error?.response?.data?.message || 'Не удалось сохранить автомобиль.'
  }
}

async function removeCar(id) {
  if (!confirm('Удалить автомобиль?')) return
  try {
    await api.delete(`/admin/cars/${id}`)
    await loadCars()
  } catch (error) {
    errorMessage.value = error?.response?.data?.message || 'Не удалось удалить автомобиль.'
  }
}

async function logout() {
  await performLogout()
}

onMounted(() => {
  document.title = 'Админ: Автомобили — CarShare'
  let meta = document.querySelector('meta[name="description"]')
  if (!meta) {
    meta = document.createElement('meta')
    meta.setAttribute('name', 'description')
    document.head.appendChild(meta)
  }
  meta.setAttribute('content', 'Раздел админ-панели CarShare для управления автопарком и тарифами автомобилей.')

  loadCars()
})
</script>

