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
          <router-link to="/app" class="text-blue-600 hover:text-blue-700 transition duration-200 ease-in-out">
            Выбор авто
          </router-link>
          <router-link to="/account" class="text-gray-600 hover:text-blue-600 transition duration-200 ease-in-out">
            Личный кабинет
          </router-link>
          <router-link to="/login" @click="logout" class="text-red-600 hover:text-red-700 transition duration-200 ease-in-out">
            Выйти
          </router-link>
        </nav>
      </div>
    </header>

    <main class="px-6 max-[767px]:px-4 max-[479px]:px-3 py-8 max-[767px]:py-6 lg:py-10">
      <section class="max-w-6xl mx-auto mb-10">
        <div class="flex flex-wrap items-start gap-8">
          <div class="flex-1 min-w-[260px]">
            <h1 class="text-3xl lg:text-4xl max-[991px]:text-2xl max-[479px]:text-xl font-bold text-gray-900 pb-3">
              Выберите автомобиль для аренды
            </h1>
            <p class="text-gray-700 text-lg max-[767px]:text-base leading-relaxed pb-4">
              Отфильтруйте парк по классу, трансмиссии и ценовому диапазону. Тарифы отображаются за минуту, час и сутки.
            </p>
          </div>
          <aside class="w-full md:w-auto md:min-w-[260px] bg-white border border-solid border-gray-200 rounded-2xl shadow-sm p-4">
            <h2 class="text-sm font-semibold text-gray-900 mb-3 uppercase tracking-wide">Быстрый фильтр</h2>
            <div class="space-y-3 text-sm">
              <div>
                <label class="block mb-1 text-gray-700" for="segment">Класс авто</label>
                <select
                  id="segment"
                  class="block w-full px-3 py-2 border border-gray-300 rounded text-sm text-gray-700 focus:outline-none focus:border-blue-600"
                  v-model="filters.segment"
                >
                  <option selected>Любой</option>
                  <option>Эконом</option>
                  <option>Комфорт</option>
                  <option>Бизнес</option>
                  <option>Электро</option>
                </select>
              </div>
              <div>
                <label class="block mb-1 text-gray-700" for="transmission">Трансмиссия</label>
                <select
                  id="transmission"
                  class="block w-full px-3 py-2 border border-gray-300 rounded text-sm text-gray-700 focus:outline-none focus:border-blue-600"
                  v-model="filters.transmission"
                >
                  <option selected>Любая</option>
                  <option>Автомат</option>
                  <option>Механика</option>
                </select>
              </div>
              <div>
                <label class="block mb-1 text-gray-700" for="price">Максимальная цена / мин</label>
                <input
                  id="price"
                  type="number"
                  min="5"
                  step="1"
                  class="block w-full px-3 py-2 border border-gray-300 rounded text-sm text-gray-700 focus:outline-none focus:border-blue-600"
                  placeholder="15"
                  v-model.number="filters.maxRateMinute"
                />
              </div>
            </div>
          </aside>
        </div>
      </section>

      <section class="max-w-6xl mx-auto mb-10" aria-label="Список доступных автомобилей">
        <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
          <h2 class="text-2xl max-[991px]:text-xl max-[479px]:text-lg font-bold text-gray-900">Доступные автомобили рядом</h2>
        </div>
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
          <article
            v-for="car in filteredCars"
            :key="car.name"
            class="bg-white border border-solid border-gray-200 rounded-2xl shadow-sm overflow-hidden flex flex-col"
          >
            <img
              v-if="car.imageUrl"
              :src="car.imageUrl"
              :alt="car.name"
              class="h-40 w-full object-cover"
            />
            <div v-else class="h-40 bg-gray-200 flex items-center justify-center text-gray-500 text-sm">
              Изображение автомобиля
            </div>
            <div class="p-4 flex-1 flex flex-col">
              <h3 class="text-lg font-bold text-gray-900 mb-1">{{ car.name }}</h3>
              <p class="text-sm text-gray-600 mb-2">{{ car.segment }} · {{ car.transmission }} · {{ car.fuel }}</p>
              <p class="text-sm text-gray-700 mb-3">{{ car.description }}</p>
              <dl class="grid grid-cols-3 max-[479px]:grid-cols-1 gap-2 text-xs text-gray-700 mb-4">
                <div>
                  <dt class="font-semibold">Минутный</dt>
                  <dd>{{ car.rateMinute }} ₽/мин</dd>
                </div>
                <div>
                  <dt class="font-semibold">Часовой</dt>
                  <dd>{{ car.rateHour }} ₽/ч</dd>
                </div>
                <div>
                  <dt class="font-semibold">Суточный</dt>
                  <dd>{{ car.rateDay }} ₽/сут</dd>
                </div>
              </dl>
              <div class="mt-auto flex items-center max-[479px]:items-start justify-between max-[479px]:flex-col gap-2">
                <p class="text-xs text-gray-500">Запас хода: {{ car.range }} км</p>
                <div class="flex flex-wrap gap-2">
                  <router-link
                    :to="`/cars/${car.id}`"
                    class="inline-block px-4 py-2 bg-white text-blue-600 font-medium text-xs leading-snug uppercase rounded border border-solid border-gray-300 shadow-sm hover:bg-gray-50 transition duration-150 ease-in-out"
                  >
                    Подробнее
                  </router-link>
                  <router-link
                    :to="`/order/${car.id}`"
                    class="inline-block px-4 py-2 bg-blue-600 text-white font-medium text-xs leading-snug uppercase rounded shadow-sm hover:bg-blue-700 focus:bg-blue-700 focus:outline-none transition duration-150 ease-in-out"
                  >
                    Выбрать
                  </router-link>
                </div>
              </div>
            </div>
          </article>
        </div>
      </section>

      <section class="max-w-6xl mx-auto mb-10 grid md:grid-cols-2 gap-6 max-[767px]:gap-4">
        <section aria-label="Текущее бронирование" class="bg-white border border-solid border-gray-200 rounded-2xl p-5 shadow-sm">
          <h2 class="text-xl font-bold text-gray-900 mb-3">Текущая поездка</h2>
          <p v-if="isOrdersLoading" class="text-sm text-gray-500">Загружаем поездки...</p>
          <p v-else-if="ordersError" class="text-sm text-red-600">{{ ordersError }}</p>
          <template v-else-if="currentTrip">
            <p class="text-sm text-gray-700 mb-1 font-semibold">{{ currentTrip.carName }}</p>
            <p class="text-sm text-gray-700 mb-1">Тариф: {{ currentTrip.tariffLabel }}</p>
            <p class="text-sm text-gray-700 mb-1">
              Период: {{ currentTrip.startAtLabel }} - {{ currentTrip.endAtLabel }}
            </p>
            <p class="text-sm text-gray-700 mb-1">Зона завершения: {{ currentTrip.endZone }}</p>
            <p class="text-sm text-gray-700 mb-1">К оплате: {{ currentTrip.totalSumLabel }}</p>
            <p class="text-sm text-gray-700">Статус: {{ currentTrip.statusLabel }}</p>
          </template>
          <p v-else class="text-sm text-gray-700">
            Сейчас нет активной поездки. Оформите заказ в разделе выбора авто, и здесь появится актуальная информация.
          </p>
        </section>

        <section aria-label="Последние поездки" class="bg-white border border-solid border-gray-200 rounded-2xl p-5 shadow-sm">
          <h2 class="text-xl font-bold text-gray-900 mb-3">Последние поездки</h2>
          <p v-if="isOrdersLoading" class="text-sm text-gray-500">Загружаем историю...</p>
          <p v-else-if="ordersError" class="text-sm text-red-600">{{ ordersError }}</p>
          <ul v-else-if="lastTrips.length" class="space-y-3 text-sm text-gray-700">
            <li v-for="trip in lastTrips" :key="trip.id" class="flex justify-between max-[479px]:flex-col gap-2">
              <span>{{ trip.title }}</span>
              <span class="text-gray-500">{{ trip.totalSumLabel }} · {{ trip.period }}</span>
            </li>
          </ul>
          <p v-else class="text-sm text-gray-700">История поездок пока пуста.</p>
        </section>
      </section>
    </main>
  </body>
</template>

<script setup>
import { computed, onBeforeUnmount, onMounted, reactive, ref } from 'vue'
import { api, performLogout } from '../../lib/api'

const cars = ref([])
const orders = ref([])
const isOrdersLoading = ref(false)
const ordersError = ref('')
const nowTick = ref(Date.now())
let refreshIntervalId = null
let nowIntervalId = null
const filters = reactive({
  segment: 'Любой',
  transmission: 'Любая',
  maxRateMinute: null,
})

const filteredCars = computed(() => {
  return cars.value.filter((car) => {
    const bySegment = filters.segment === 'Любой' || car.segment === filters.segment
    const byTransmission = filters.transmission === 'Любая' || car.transmission === filters.transmission
    const byPrice =
      !filters.maxRateMinute || Number(car.rateMinute) <= Number(filters.maxRateMinute)
    return bySegment && byTransmission && byPrice
  })
})

async function loadCars() {
  const response = await api.get('/cars')
  const rawCars = response?.data?.data || []
  cars.value = rawCars.map((car) => ({
    id: car.id,
    name: car.name,
    segment: car.segment,
    transmission: car.transmission,
    fuel: car.fuel,
    description: car.description,
    imageUrl: car.image_url || '',
    rateMinute: car.rate_minute,
    rateHour: car.rate_hour,
    rateDay: car.rate_day,
    range: car.range_km,
  }))
}

function formatDateTime(value) {
  const date = parseApiDateTime(value)
  if (!date) return '—'
  return date.toLocaleString('ru-RU', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}

function parseApiDateTime(value) {
  if (!value) return null
  const normalized = String(value).replace('T', ' ').replace('Z', '').slice(0, 19)
  const match = normalized.match(
    /^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2})(?::(\d{2}))?$/,
  )
  if (!match) return null
  const date = new Date(
    Number(match[1]),
    Number(match[2]) - 1,
    Number(match[3]),
    Number(match[4]),
    Number(match[5]),
    Number(match[6] || 0),
  )
  if (Number.isNaN(date.getTime())) return null
  return date
}

function mapOrderStatusLabel(status) {
  if (status === 'active') return 'В пути'
  if (status === 'created') return 'Запланирована'
  if (status === 'completed' || status === 'finished') return 'Завершена'
  if (status === 'cancelled') return 'Отменена'
  return status || '—'
}

function mapTariffLabel(tariff) {
  if (tariff === 'minute') return 'Поминутный'
  if (tariff === 'hour') return 'Почасовой'
  if (tariff === 'day') return 'Посуточный'
  return '—'
}

function formatMoney(value) {
  const amount = Number(value)
  if (Number.isNaN(amount)) return '—'
  return `${amount.toLocaleString('ru-RU')} ₽`
}

function mapOrderTrip(order) {
  const effectiveStatus = getEffectiveOrderStatus(order)
  return {
    id: order.id,
    carName: order?.car?.name || 'Автомобиль',
    tariffLabel: mapTariffLabel(order.tariff),
    startAtLabel: formatDateTime(order.start_at),
    endAtLabel: formatDateTime(order.end_at),
    endZone: order.end_zone || 'Не указана',
    statusLabel: mapOrderStatusLabel(effectiveStatus),
    totalSumLabel: formatMoney(order.total_sum),
    period: `${formatDateTime(order.start_at)} - ${formatDateTime(order.end_at)}`,
    title: `${order?.car?.name || 'Автомобиль'} · ${mapTariffLabel(order.tariff)}`,
    startAtRaw: order.start_at,
    endAtRaw: order.end_at,
    status: effectiveStatus,
  }
}

function getEffectiveOrderStatus(order) {
  if (order.status === 'cancelled') return 'cancelled'
  const now = new Date(nowTick.value)
  const startAt = parseApiDateTime(order.start_at)
  const endAt = parseApiDateTime(order.end_at)
  if (!startAt || !endAt) {
    return order.status
  }
  if (endAt <= now) return 'finished'
  if (startAt <= now) return 'active'
  return 'created'
}

async function loadOrders() {
  isOrdersLoading.value = true
  ordersError.value = ''
  try {
    const response = await api.get('/orders')
    orders.value = response?.data?.data || []
  } catch (error) {
    ordersError.value = error?.response?.data?.message || 'Не удалось загрузить поездки.'
    orders.value = []
  } finally {
    isOrdersLoading.value = false
  }
}

const currentTrip = computed(() => {
  const activeNow = orders.value.find((order) => getEffectiveOrderStatus(order) === 'active')
  if (activeNow) return mapOrderTrip(activeNow)

  const upcomingCreated = orders.value.find((order) => getEffectiveOrderStatus(order) === 'created')
  return upcomingCreated ? mapOrderTrip(upcomingCreated) : null
})

const lastTrips = computed(() => {
  const currentTripId = currentTrip.value?.id
  return orders.value
    .filter((order) => order.id !== currentTripId)
    .filter((order) => {
      const status = getEffectiveOrderStatus(order)
      return status === 'finished' || status === 'cancelled'
    })
    .slice(0, 5)
    .map((order) => mapOrderTrip(order))
})

onMounted(() => {
  document.title = 'Выбор автомобиля — CarShare'
  let meta = document.querySelector('meta[name="description"]')
  if (!meta) {
    meta = document.createElement('meta')
    meta.setAttribute('name', 'description')
    document.head.appendChild(meta)
  }
  meta.setAttribute('content', 'Каталог доступных автомобилей CarShare с фильтрами и блоками текущих и последних поездок.')

  loadCars().catch((error) => {
    console.error('Cars load error:', error)
  })
  loadOrders().catch((error) => {
    console.error('Orders load error:', error)
  })
  nowIntervalId = setInterval(() => {
    nowTick.value = Date.now()
  }, 30000)
  refreshIntervalId = setInterval(() => {
    loadOrders().catch((error) => {
      console.error('Orders refresh error:', error)
    })
  }, 60000)
})

async function logout() {
  await performLogout()
}

onBeforeUnmount(() => {
  if (refreshIntervalId) clearInterval(refreshIntervalId)
  if (nowIntervalId) clearInterval(nowIntervalId)
})

</script>
