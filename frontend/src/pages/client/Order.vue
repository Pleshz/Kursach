<template>
  <body class="min-h-screen bg-gray-50 text-gray-800">
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
            <h1 class="text-3xl lg:text-4xl max-[991px]:text-2xl max-[479px]:text-xl font-bold text-gray-900 pb-2">Оформление заказа</h1>
            <p class="text-gray-700 text-lg max-[767px]:text-base leading-relaxed">
              Вы выбрали автомобиль. Укажите тариф и параметры аренды, затем подтвердите заказ.
            </p>
          </div>
          <router-link
            to="/app"
            class="inline-block px-5 py-2 bg-white text-blue-600 font-medium text-xs leading-snug uppercase rounded border border-solid border-gray-300 shadow-sm hover:bg-gray-50 transition duration-150 ease-in-out"
          >
            Назад к выбору
          </router-link>
        </div>
      </section>

      <section class="max-w-6xl mx-auto grid lg:grid-cols-3 gap-6 max-[767px]:gap-4">
        <section class="lg:col-span-2 bg-white border border-solid border-gray-200 rounded-2xl shadow-sm overflow-hidden">
          <div class="px-5 py-4 border-b border-solid border-gray-200">
            <h2 class="text-xl font-bold text-gray-900">Карта и зона завершения</h2>
            <p class="text-sm text-gray-500 pt-1">Кликните по карте, чтобы выбрать зону завершения. Адрес подставится автоматически.</p>
            </div>
          <div class="p-5">
            <div ref="mapEl" class="h-[360px] max-[767px]:h-[280px] max-[479px]:h-[220px] w-full rounded-xl border border-solid border-gray-200"></div>
          </div>
        </section>

        <aside class="bg-white border border-solid border-gray-200 rounded-2xl shadow-sm p-5">
          <h2 class="text-xl font-bold text-gray-900 mb-4">Автомобиль</h2>

          <div class="mb-4 rounded-xl border border-solid border-gray-200 bg-gray-50 p-4">
            <p class="text-lg font-bold text-gray-900">{{ selectedCar.name }}</p>
            <p class="text-sm text-gray-600 pt-1">
              {{ selectedCar.segment }} · {{ selectedCar.transmission }} · {{ selectedCar.fuel }}
            </p>
            <p class="text-sm text-gray-700 pt-2">{{ selectedCar.description }}</p>
          </div>
          <p v-if="isCarLoading" class="text-sm text-gray-500 mb-3">Загружаем данные автомобиля...</p>
          <div
            v-if="documentsLoaded && !isDocumentsVerified"
            class="mb-4 rounded-xl border border-red-200 bg-red-50 p-3 text-sm text-red-700"
          >
            Для оформления заказа нужно подтвердить документы: {{ missingDocumentLabels.join(', ') }}.
            <router-link to="/account/documents" class="ml-1 font-semibold underline">Перейти к загрузке</router-link>
          </div>

          <form class="space-y-3 text-sm text-gray-700" @submit.prevent="submitOrder">
            <div>
              <label for="tariff" class="block mb-1 text-gray-700">Тариф</label>
              <select
                id="tariff"
                class="block w-full px-3 py-2 border border-gray-300 rounded text-sm text-gray-700 focus:outline-none focus:border-blue-600"
                v-model="form.tariff"
              >
                <option value="minute">Минутный ({{ selectedCar.rateMinute }} ₽/мин)</option>
                <option value="hour">Почасовой ({{ selectedCar.rateHour }} ₽/ч)</option>
                <option value="day">Суточный ({{ selectedCar.rateDay }} ₽/сут)</option>
              </select>
            </div>

            <div>
              <label for="start" class="block mb-1 text-gray-700">Начало аренды</label>
              <input
                id="start"
                type="datetime-local"
                class="block w-full px-3 py-2 border border-gray-300 rounded text-sm text-gray-700 focus:outline-none focus:border-blue-600"
                v-model="form.start_at"
              />
            </div>

            <div v-if="form.tariff !== 'day'">
              <label for="endAt" class="block mb-1 text-gray-700">Окончание аренды</label>
              <input
                id="endAt"
                type="datetime-local"
                class="block w-full px-3 py-2 border border-gray-300 rounded text-sm text-gray-700 focus:outline-none focus:border-blue-600"
                v-model="form.end_at"
              />
            </div>

            <div v-else>
              <label for="rentalDays" class="block mb-1 text-gray-700">Количество дней аренды</label>
              <input
                id="rentalDays"
                type="number"
                min="1"
                step="1"
                class="block w-full px-3 py-2 border border-gray-300 rounded text-sm text-gray-700 focus:outline-none focus:border-blue-600"
                v-model.number="form.rental_days"
              />
              <p class="text-xs text-gray-500 mt-1">Окончание будет рассчитано автоматически.</p>
            </div>

            <div>
              <label for="endZone" class="block mb-1 text-gray-700">Планируемая зона завершения</label>
              <input
                id="endZone"
                type="text"
                class="block w-full px-3 py-2 border border-gray-300 rounded text-sm text-gray-700 bg-gray-50 focus:outline-none focus:border-blue-600"
                placeholder="Выберите точку на карте"
                v-model="form.end_zone"
                readonly
              />
            </div>

            <button
              type="submit"
              :disabled="isSubmitting || isCarLoading || !selectedCar.id || (documentsLoaded && !isDocumentsVerified)"
              class="mt-5 w-full inline-block px-7 py-3 bg-blue-600 text-white font-medium text-sm leading-snug uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out disabled:bg-gray-400 disabled:cursor-not-allowed"
            >
              {{ isSubmitting ? 'Оформляем...' : 'Подтвердить заказ' }}
            </button>
          </form>
          <p v-if="estimatedTotal !== null" class="text-sm text-gray-700 mt-3">
            Предварительная сумма: {{ formatMoney(estimatedTotal) }}
          </p>
          <p v-if="createdOrderTotal !== null" class="text-sm text-gray-700 mt-1">
            Сумма заказа: {{ formatMoney(createdOrderTotal) }}
          </p>
          <p v-if="statusMessage" class="text-sm text-green-700 mt-3">{{ statusMessage }}</p>
          <p v-if="errorMessage" class="text-sm text-red-600 mt-3">{{ errorMessage }}</p>
        </aside>
      </section>
    </main>
  </body>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, reactive, ref, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import L from 'leaflet'
import { api, performLogout } from '../../lib/api'

const route = useRoute()
const router = useRouter()
const mapEl = ref(null)
let mapInstance = null
let endZoneMarker = null
let carMarker = null

const selectedCar = ref({
  id: null,
  name: 'Загрузка...',
  segment: '—',
  transmission: '—',
  fuel: '—',
  description: '',
  rateMinute: 0,
  rateHour: 0,
  rateDay: 0,
  latitude: null,
  longitude: null,
  isAvailable: true,
})

const form = reactive({
  tariff: 'minute',
  start_at: '',
  end_at: '',
  rental_days: 1,
  end_zone: '',
})
const statusMessage = ref('')
const errorMessage = ref('')
const isSubmitting = ref(false)
const isCarLoading = ref(false)
const createdOrderTotal = ref(null)
const documentsLoaded = ref(false)
const documentStatuses = reactive({
  passport: 'missing',
  license: 'missing',
  selfie: 'missing',
})

const requiredDocumentTypes = ['passport', 'license', 'selfie']

function mapDocumentTypeLabel(type) {
  if (type === 'passport') return 'паспорт'
  if (type === 'license') return 'водительское удостоверение'
  if (type === 'selfie') return 'селфи с документом'
  return type
}

const isDocumentsVerified = computed(() => {
  return requiredDocumentTypes.every((type) => documentStatuses[type] === 'verified')
})

const missingDocumentLabels = computed(() => {
  return requiredDocumentTypes
    .filter((type) => documentStatuses[type] !== 'verified')
    .map((type) => mapDocumentTypeLabel(type))
})

async function logout() {
  await performLogout()
}

async function loadCar() {
  isCarLoading.value = true
  const carId = Number(route.params.carId)
  if (!carId) {
    throw new Error('Некорректный ID автомобиля.')
  }
  const response = await api.get(`/cars/${carId}`)
  const car = response?.data?.data
  if (!car?.id) {
    throw new Error('Автомобиль не найден.')
  }
  selectedCar.value = {
    id: car.id,
    name: car.name,
    segment: car.segment,
    transmission: car.transmission,
    fuel: car.fuel,
    description: car.description,
    rateMinute: car.rate_minute,
    rateHour: car.rate_hour,
    rateDay: car.rate_day,
    latitude: car.latitude ? Number(car.latitude) : null,
    longitude: car.longitude ? Number(car.longitude) : null,
    isAvailable: car.is_available !== false,
  }
  if (!selectedCar.value.isAvailable) {
    throw new Error('Автомобиль сейчас недоступен. Его уже арендовали.')
  }

  if (mapInstance && selectedCar.value.latitude && selectedCar.value.longitude) {
    const carPoint = [selectedCar.value.latitude, selectedCar.value.longitude]
    mapInstance.setView(carPoint, 14)
    if (carMarker) {
      carMarker.setLatLng(carPoint)
    } else {
      carMarker = L.marker(carPoint).addTo(mapInstance)
    }
    carMarker.bindPopup('Местоположение автомобиля').openPopup()
  }
  isCarLoading.value = false
}

async function loadDocumentsState() {
  documentsLoaded.value = false
  for (const type of requiredDocumentTypes) {
    documentStatuses[type] = 'missing'
  }

  try {
    const response = await api.get('/documents')
    const docs = response?.data?.data || []
    for (const type of requiredDocumentTypes) {
      const hasVerified = docs.some((doc) => doc.type === type && doc.status === 'verified')
      const hasUploaded = docs.some((doc) => doc.type === type && doc.status === 'uploaded')
      const hasRejected = docs.some((doc) => doc.type === type && doc.status === 'rejected')
      if (hasVerified) {
        documentStatuses[type] = 'verified'
      } else if (hasUploaded) {
        documentStatuses[type] = 'uploaded'
      } else if (hasRejected) {
        documentStatuses[type] = 'rejected'
      } else {
        documentStatuses[type] = 'missing'
      }
    }
  } catch (error) {
    // no-op: backend check will still block order creation
  } finally {
    documentsLoaded.value = true
  }
}

function normalizeValidationMessage(error) {
  const messages = error?.response?.data?.errors
  if (!messages || typeof messages !== 'object') return null
  const firstKey = Object.keys(messages)[0]
  if (!firstKey) return null
  const firstMessage = messages[firstKey]?.[0]
  return firstMessage || null
}

function toApiDateTime(datetimeLocal) {
  return datetimeLocal ? `${datetimeLocal}:00` : ''
}

function toDatetimeLocal(date) {
  const pad = (value) => String(value).padStart(2, '0')
  return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`
}

function recalculateEndAtForDayTariff() {
  if (form.tariff !== 'day') return
  if (!form.start_at) return
  const startDate = new Date(form.start_at)
  if (Number.isNaN(startDate.getTime())) return
  const days = Math.max(1, Number(form.rental_days) || 1)
  const endDate = new Date(startDate.getTime() + days * 24 * 60 * 60 * 1000)
  form.end_at = toDatetimeLocal(endDate)
}

function formatMoney(value) {
  const amount = Number(value)
  if (Number.isNaN(amount)) return '—'
  return `${amount.toLocaleString('ru-RU')} ₽`
}

const estimatedTotal = computed(() => {
  const startDate = new Date(form.start_at)
  const endDate = new Date(form.end_at)
  if (Number.isNaN(startDate.getTime()) || Number.isNaN(endDate.getTime())) return null
  const diffMs = endDate.getTime() - startDate.getTime()
  if (diffMs <= 0) return null

  const minutes = Math.ceil(diffMs / (60 * 1000))
  const hours = Math.ceil(diffMs / (60 * 60 * 1000))
  const days = Math.ceil(diffMs / (24 * 60 * 60 * 1000))

  if (form.tariff === 'hour') return hours * Number(selectedCar.value.rateHour || 0)
  if (form.tariff === 'day') return days * Number(selectedCar.value.rateDay || 0)
  return minutes * Number(selectedCar.value.rateMinute || 0)
})

async function reverseGeocode(lat, lng) {
  const url = `https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}&accept-language=ru`
  const response = await fetch(url, {
    headers: {
      Accept: 'application/json',
    },
  })
  if (!response.ok) throw new Error('Geocoding failed')
  const data = await response.json()
  return data?.display_name || ''
}

async function submitOrder() {
  if (isSubmitting.value) return
  errorMessage.value = ''
  statusMessage.value = ''

  if (!selectedCar.value.id) {
    errorMessage.value = 'Автомобиль не загружен. Вернитесь к списку и выберите снова.'
    return
  }
  if (!form.start_at) {
    errorMessage.value = 'Укажите дату и время начала аренды.'
    return
  }
  if (!form.end_at) {
    errorMessage.value = 'Укажите дату и время окончания аренды.'
    return
  }
  if (!form.end_zone.trim()) {
    errorMessage.value = 'Укажите планируемую зону завершения.'
    return
  }
  if (documentsLoaded.value && !isDocumentsVerified.value) {
    errorMessage.value = 'Сначала подтвердите документы, затем оформляйте заказ.'
    return
  }

  isSubmitting.value = true
  try {
    const response = await api.post('/orders', {
      car_id: selectedCar.value.id,
      tariff: form.tariff,
      start_at: toApiDateTime(form.start_at),
      end_at: toApiDateTime(form.end_at),
      end_zone: form.end_zone.trim(),
    })
    const orderId = response?.data?.data?.id
    createdOrderTotal.value = response?.data?.data?.total_sum ?? null
    statusMessage.value = orderId
      ? `Заказ успешно оформлен.`
      : 'Заказ успешно оформлен.'
    await router.push('/app')
  } catch (error) {
    errorMessage.value =
      normalizeValidationMessage(error) ||
      error?.response?.data?.message ||
      'Не удалось оформить заказ.'
  } finally {
    isSubmitting.value = false
  }
}

onMounted(() => {
  document.title = 'Оформление заказа — CarShare'
  let meta = document.querySelector('meta[name="description"]')
  if (!meta) {
    meta = document.createElement('meta')
    meta.setAttribute('name', 'description')
    document.head.appendChild(meta)
  }
  meta.setAttribute('content', 'Страница оформления аренды автомобиля: выбор тарифа, времени поездки и зоны завершения.')

  const now = new Date()
  const plusHour = new Date(now.getTime() + 60 * 60 * 1000)
  form.start_at = toDatetimeLocal(now)
  form.end_at = toDatetimeLocal(plusHour)

  loadCar().catch((error) => {
    errorMessage.value = error?.response?.data?.message || 'Не удалось загрузить автомобиль.'
  }).finally(() => {
    isCarLoading.value = false
  })
  loadDocumentsState()

  if (!mapEl.value) return

  const center = [58.0105, 56.2502]
  mapInstance = L.map(mapEl.value, {
    center,
    zoom: 12,
    zoomControl: true,
  })

  L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors',
  }).addTo(mapInstance)

  L.marker(center).addTo(mapInstance).bindPopup('Выберите точку завершения аренды')
  mapInstance.on('click', (event) => {
    const { lat, lng } = event.latlng
    const coordinates = `${lat.toFixed(5)}, ${lng.toFixed(5)}`
    form.end_zone = `Определяем адрес... (${coordinates})`

    if (endZoneMarker) {
      endZoneMarker.setLatLng(event.latlng)
    } else {
      endZoneMarker = L.marker(event.latlng).addTo(mapInstance)
    }
    endZoneMarker.bindPopup(`Зона завершения: ${coordinates}`).openPopup()

    reverseGeocode(lat, lng)
      .then((address) => {
        form.end_zone = address || coordinates
      })
      .catch(() => {
        form.end_zone = coordinates
      })
  })
})

watch(
  () => [form.tariff, form.start_at, form.rental_days],
  () => {
    recalculateEndAtForDayTariff()
  },
  { immediate: true },
)

onBeforeUnmount(() => {
  if (mapInstance) {
    mapInstance.off()
    mapInstance.remove()
    mapInstance = null
  }
  carMarker = null
  endZoneMarker = null
})
</script>

