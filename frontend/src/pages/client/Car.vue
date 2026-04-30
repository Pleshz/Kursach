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
          <template v-if="isAuth">
            <router-link to="/login" @click="logout" class="text-red-600 hover:text-red-700 transition duration-200 ease-in-out">
              Выйти
            </router-link>
          </template>
          <template v-else>
            <router-link to="/login" class="text-blue-600 hover:text-blue-700 transition duration-200 ease-in-out">
              Войти
            </router-link>
          </template>
        </nav>
      </div>
    </header>

    <main class="px-6 max-[767px]:px-4 max-[479px]:px-3 py-8 max-[767px]:py-6 lg:py-10">
      <section class="max-w-6xl mx-auto mb-6">
        <router-link
          to="/app"
          class="inline-block px-4 py-2 bg-white text-blue-600 font-medium text-xs leading-snug uppercase rounded border border-solid border-gray-300 shadow-sm hover:bg-gray-50 transition duration-150 ease-in-out"
        >
          ← Назад к каталогу
        </router-link>
      </section>

      <p v-if="isCarLoading" class="max-w-6xl mx-auto text-sm text-gray-500">Загружаем автомобиль...</p>
      <p v-else-if="loadError" class="max-w-6xl mx-auto text-sm text-red-600">{{ loadError }}</p>

      <template v-else-if="car.id">
        <section class="max-w-6xl mx-auto mb-8 grid lg:grid-cols-2 gap-6 max-[767px]:gap-4">
          <div class="bg-white border border-solid border-gray-200 rounded-2xl shadow-sm overflow-hidden">
            <img
              v-if="car.imageUrl"
              :src="car.imageUrl"
              :alt="car.name"
              class="h-72 max-[767px]:h-56 w-full object-cover"
            />
            <div v-else class="h-72 max-[767px]:h-56 bg-gray-200 flex items-center justify-center text-gray-500 text-sm">
              Изображение автомобиля
            </div>
          </div>

          <div class="bg-white border border-solid border-gray-200 rounded-2xl shadow-sm p-5">
            <h1 class="text-3xl lg:text-4xl max-[991px]:text-2xl max-[479px]:text-xl font-bold text-gray-900 pb-2">
              {{ car.name }}
            </h1>
            <p class="text-sm text-gray-600 mb-3">{{ car.segment }} · {{ car.transmission }} · {{ car.fuel }}</p>

            <div class="flex items-center gap-2 mb-4" aria-label="Средний рейтинг">
              <span class="text-yellow-500 text-lg" aria-hidden="true">{{ ratingStars(averageRating) }}</span>
              <span class="text-sm text-gray-700">
                {{ averageRating !== null ? averageRating.toFixed(1) : '—' }}
                <span class="text-gray-500">({{ reviewsCount }} {{ pluralizeReviews(reviewsCount) }})</span>
              </span>
            </div>

            <p class="text-base text-gray-700 mb-5">{{ car.description }}</p>

            <dl class="grid grid-cols-3 max-[479px]:grid-cols-1 gap-3 text-sm text-gray-700 mb-5">
              <div class="rounded-lg border border-gray-200 p-3">
                <dt class="font-semibold">Минутный</dt>
                <dd>{{ car.rateMinute }} ₽/мин</dd>
              </div>
              <div class="rounded-lg border border-gray-200 p-3">
                <dt class="font-semibold">Часовой</dt>
                <dd>{{ car.rateHour }} ₽/ч</dd>
              </div>
              <div class="rounded-lg border border-gray-200 p-3">
                <dt class="font-semibold">Суточный</dt>
                <dd>{{ car.rateDay }} ₽/сут</dd>
              </div>
            </dl>

            <p class="text-sm text-gray-600 mb-5">Запас хода: {{ car.range }} км</p>

            <div v-if="car.isAvailable" class="flex flex-wrap gap-3">
              <router-link
                :to="`/order/${car.id}`"
                class="inline-block px-7 py-3 bg-blue-600 text-white font-medium text-sm leading-snug uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:outline-none transition duration-150 ease-in-out"
              >
                Арендовать
              </router-link>
            </div>
            <p v-else class="text-sm text-red-600">Автомобиль сейчас недоступен.</p>
          </div>
        </section>

        <section class="max-w-6xl mx-auto mb-10 bg-white border border-solid border-gray-200 rounded-2xl shadow-sm p-5" aria-label="Отзывы об автомобиле">
          <h2 class="text-2xl max-[991px]:text-xl max-[479px]:text-lg font-bold text-gray-900 mb-4">
            Отзывы клиентов
          </h2>

          <div v-if="isAuth" class="mb-6 rounded-xl border border-solid border-gray-200 bg-gray-50 p-4">
            <h3 class="text-lg font-bold text-gray-900 mb-3">Оставить отзыв</h3>
            <form class="space-y-3 text-sm text-gray-700" @submit.prevent="submitReview">
              <div>
                <label for="rating" class="block mb-1 text-gray-700">Оценка</label>
                <select
                  id="rating"
                  v-model.number="reviewForm.rating"
                  class="block w-full max-w-xs px-3 py-2 border border-gray-300 rounded text-sm text-gray-700 focus:outline-none focus:border-blue-600"
                >
                  <option :value="5">5 — Отлично</option>
                  <option :value="4">4 — Хорошо</option>
                  <option :value="3">3 — Нормально</option>
                  <option :value="2">2 — Плохо</option>
                  <option :value="1">1 — Ужасно</option>
                </select>
              </div>
              <div>
                <label for="comment" class="block mb-1 text-gray-700">Комментарий</label>
                <textarea
                  id="comment"
                  v-model="reviewForm.comment"
                  rows="4"
                  maxlength="2000"
                  required
                  class="block w-full px-3 py-2 border border-gray-300 rounded text-sm text-gray-700 focus:outline-none focus:border-blue-600"
                  placeholder="Поделитесь впечатлением от поездки"
                ></textarea>
                <p class="text-xs text-gray-500 mt-1">Не менее 5 и не более 2000 символов.</p>
              </div>
              <button
                type="submit"
                :disabled="isSubmittingReview"
                class="inline-block px-5 py-2 bg-blue-600 text-white font-medium text-xs leading-snug uppercase rounded shadow-sm hover:bg-blue-700 focus:outline-none transition duration-150 ease-in-out disabled:bg-gray-400 disabled:cursor-not-allowed"
              >
                {{ isSubmittingReview ? 'Отправляем...' : 'Опубликовать отзыв' }}
              </button>
              <p v-if="reviewSuccess" class="text-sm text-green-700">{{ reviewSuccess }}</p>
              <p v-if="reviewError" class="text-sm text-red-600">{{ reviewError }}</p>
            </form>
          </div>
          <div v-else class="mb-6 rounded-xl border border-solid border-gray-200 bg-gray-50 p-4 text-sm text-gray-700">
            <router-link to="/login" class="text-blue-600 hover:text-blue-700 font-semibold">Войдите</router-link>
            в аккаунт, чтобы оставить отзыв после поездки.
          </div>

          <div v-if="isReviewsLoading" class="text-sm text-gray-500">Загружаем отзывы...</div>
          <ul v-else-if="reviews.length" class="space-y-4">
            <li
              v-for="review in reviews"
              :key="review.id"
              class="rounded-xl border border-solid border-gray-200 p-4"
            >
              <div class="flex items-center justify-between gap-3 mb-1">
                <p class="font-semibold text-gray-900">{{ review.user?.name || 'Клиент CarShare' }}</p>
                <span class="text-yellow-500 text-sm" aria-hidden="true">{{ ratingStars(review.rating) }}</span>
              </div>
              <p class="text-xs text-gray-500 mb-2">{{ formatDate(review.created_at) }}</p>
              <p class="text-sm text-gray-700 whitespace-pre-line">{{ review.comment }}</p>
            </li>
          </ul>
          <p v-else class="text-sm text-gray-700">Об этом автомобиле пока нет отзывов. Станьте первым!</p>
        </section>
      </template>
    </main>
  </body>
</template>

<script setup>
import { computed, onMounted, reactive, ref } from 'vue'
import { useRoute } from 'vue-router'
import { api, getAuthToken, performLogout } from '../../lib/api'

const route = useRoute()

const car = ref({
  id: null,
  slug: '',
  name: '',
  segment: '',
  transmission: '',
  fuel: '',
  description: '',
  imageUrl: '',
  rateMinute: 0,
  rateHour: 0,
  rateDay: 0,
  range: 0,
  isAvailable: true,
})
const isCarLoading = ref(false)
const loadError = ref('')

const reviews = ref([])
const reviewsCount = ref(0)
const averageRating = ref(null)
const isReviewsLoading = ref(false)

const reviewForm = reactive({
  rating: 5,
  comment: '',
})
const isSubmittingReview = ref(false)
const reviewError = ref('')
const reviewSuccess = ref('')

const isAuth = computed(() => !!getAuthToken())

function ratingStars(value) {
  if (value === null || value === undefined) return '☆☆☆☆☆'
  const rounded = Math.round(Number(value))
  return '★'.repeat(rounded) + '☆'.repeat(Math.max(0, 5 - rounded))
}

function pluralizeReviews(count) {
  const n = Math.abs(Number(count) || 0) % 100
  const n1 = n % 10
  if (n > 10 && n < 20) return 'отзывов'
  if (n1 > 1 && n1 < 5) return 'отзыва'
  if (n1 === 1) return 'отзыв'
  return 'отзывов'
}

function formatDate(value) {
  if (!value) return ''
  const date = new Date(String(value).replace(' ', 'T'))
  if (Number.isNaN(date.getTime())) return ''
  return date.toLocaleString('ru-RU', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
  })
}

function applyMeta(title, description) {
  document.title = title
  let meta = document.querySelector('meta[name="description"]')
  if (!meta) {
    meta = document.createElement('meta')
    meta.setAttribute('name', 'description')
    document.head.appendChild(meta)
  }
  meta.setAttribute('content', description)
}

async function loadCar() {
  isCarLoading.value = true
  loadError.value = ''
  try {
    const carId = Number(route.params.carId)
    if (!carId) throw new Error('Некорректный ID автомобиля.')
    const response = await api.get(`/cars/${carId}`)
    const data = response?.data?.data
    if (!data?.id) throw new Error('Автомобиль не найден.')
    car.value = {
      id: data.id,
      slug: data.slug,
      name: data.name,
      segment: data.segment,
      transmission: data.transmission,
      fuel: data.fuel,
      description: data.description,
      imageUrl: data.image_url || '',
      rateMinute: data.rate_minute,
      rateHour: data.rate_hour,
      rateDay: data.rate_day,
      range: data.range_km,
      isAvailable: data.is_available !== false,
    }
    reviewsCount.value = data.reviews_count ?? 0
    averageRating.value = data.reviews_average !== null && data.reviews_average !== undefined
      ? Number(data.reviews_average)
      : null

    applyMeta(
      `${car.value.name} — аренда автомобиля в CarShare`,
      `${car.value.name}: ${car.value.segment}, ${car.value.transmission}, ${car.value.fuel}. Тарифы, описание, отзывы клиентов и онлайн-бронирование в CarShare.`,
    )
  } catch (error) {
    loadError.value = error?.response?.data?.message || error?.message || 'Не удалось загрузить автомобиль.'
  } finally {
    isCarLoading.value = false
  }
}

async function loadReviews() {
  if (!car.value.id && !route.params.carId) return
  const carId = car.value.id || Number(route.params.carId)
  isReviewsLoading.value = true
  try {
    const response = await api.get(`/cars/${carId}/reviews`)
    reviews.value = response?.data?.data || []
    reviewsCount.value = response?.data?.meta?.count ?? reviews.value.length
    averageRating.value = response?.data?.meta?.average ?? null
  } catch (error) {
    reviews.value = []
  } finally {
    isReviewsLoading.value = false
  }
}

async function submitReview() {
  if (isSubmittingReview.value) return
  reviewError.value = ''
  reviewSuccess.value = ''

  if (!reviewForm.comment.trim() || reviewForm.comment.trim().length < 5) {
    reviewError.value = 'Комментарий должен содержать не менее 5 символов.'
    return
  }

  isSubmittingReview.value = true
  try {
    await api.post(`/cars/${car.value.id}/reviews`, {
      rating: reviewForm.rating,
      comment: reviewForm.comment.trim(),
    })
    reviewSuccess.value = 'Спасибо! Ваш отзыв опубликован.'
    reviewForm.rating = 5
    reviewForm.comment = ''
    await loadReviews()
  } catch (error) {
    reviewError.value =
      error?.response?.data?.message ||
      error?.response?.data?.errors?.comment?.[0] ||
      error?.response?.data?.errors?.rating?.[0] ||
      'Не удалось отправить отзыв.'
  } finally {
    isSubmittingReview.value = false
  }
}

async function logout() {
  await performLogout()
}

onMounted(async () => {
  applyMeta('Автомобиль — CarShare', 'Подробная информация об автомобиле CarShare: характеристики, тарифы и отзывы клиентов.')
  await loadCar()
  await loadReviews()
})
</script>
