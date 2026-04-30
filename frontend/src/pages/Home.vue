<template>
  <body class="min-h-screen bg-gray-50 text-gray-800">
    <header class="border-b border-solid border-gray-200 bg-white">
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
      <div class="px-6 max-[767px]:px-4 max-[479px]:px-3 py-4 flex flex-wrap items-center justify-between gap-4 max-w-6xl mx-auto">
        <router-link to="/" class="text-2xl font-bold text-gray-900 no-underline">
          CarShare
        </router-link>
        <nav class="flex flex-wrap items-center gap-4 text-sm font-semibold" aria-label="Основная навигация">
          <router-link to="/login" class="text-blue-600 hover:text-blue-700 transition duration-200 ease-in-out">
            Войти
          </router-link>
          <router-link
            to="/register"
            class="text-red-600 hover:text-red-700 transition duration-200 ease-in-out"
          >
            Регистрация
          </router-link>
        </nav>
      </div>
    </header>
    

    <main>
      <section class="px-6 max-[767px]:px-4 max-[479px]:px-3 py-12 max-[767px]:py-8 max-[479px]:py-6 lg:py-16">
        <div class="max-w-6xl mx-auto">
          <div class="flex xl:justify-center lg:justify-between justify-center items-center flex-wrap g-6">
            <div class="grow-0 shrink-1 md:shrink-0 basis-auto xl:w-6/12 lg:w-6/12 md:w-9/12 mb-12 md:mb-0">
              <img
                src="https://images.unsplash.com/photo-1603584173870-7f23fdae1b7a?q=80&w=1169&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                class="w-full rounded shadow-md"
                alt="Аренда автомобиля через сервис каршеринга CarShare"
                width="800"
                height="600"
                decoding="async"
              />
            </div>
            <div class="xl:ml-20 xl:w-5/12 lg:w-5/12 md:w-8/12 mb-12 md:mb-0">
              <h1 class="text-3xl max-[991px]:text-2xl max-[479px]:text-xl lg:text-4xl font-bold text-gray-900 pb-4">
                Каршеринг CarShare — аренда авто по минутам и часам
              </h1>
              <p class="text-lg max-[767px]:text-base text-gray-700 pb-6 leading-relaxed">
                Забирайте автомобиль в нескольких кликах: онлайн-бронирование, прозрачные тарифы, страхование и техподдержка 24/7.
                Подходит для поездок по городу и за его пределами.
              </p>
              <div class="flex flex-wrap gap-4 text-center lg:text-left">
                <router-link
                  to="/register"
                  class="inline-block px-7 py-3 bg-blue-600 text-white font-medium text-sm leading-snug uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out"
                >
                  Начать
                </router-link>
                <router-link
                  to="/login"
                  class="inline-block px-7 py-3 bg-white text-blue-600 font-medium text-sm leading-snug uppercase rounded border border-solid border-gray-300 shadow-sm hover:bg-gray-50 transition duration-150 ease-in-out"
                >
                  Войти в аккаунт
                </router-link>
              </div>
            </div>
          </div>
        </div>
      </section>

      <section id="about" class="px-6 max-[767px]:px-4 max-[479px]:px-3 py-12 max-[767px]:py-8 max-[479px]:py-6 bg-white border-y border-solid border-gray-200">
        <div class="max-w-6xl mx-auto">
          <h2 class="text-3xl max-[991px]:text-2xl max-[479px]:text-xl font-bold pb-6 text-gray-900">О компании CarShare</h2>
          <div class="grid md:grid-cols-2 gap-8 max-[767px]:gap-5 text-gray-700 leading-relaxed text-lg max-[767px]:text-base">
            <div>
              <p class="pb-4">
                CarShare — сервис краткосрочной аренды автомобилей (каршеринг) для жителей и гостей города.
                Мы объединяем парк современных машин, удобные зоны выдачи и цифровой сервис без очередей в офисе.
              </p>
              <p>
                Наша миссия — сделать мобильность доступной: вы платите только за время использования, без скрытых платежей.
                В личном кабинете доступна история поездок, чеки и расшифровка тарифа.
              </p>
            </div>
            <ul class="list-none p-0 m-0 space-y-3">
              <li class="flex gap-3">
                <span class="font-bold text-blue-600 shrink-0">•</span>
                <span>Лицензированная деятельность и договор оферты в электронном виде</span>
              </li>
              <li class="flex gap-3">
                <span class="font-bold text-blue-600 shrink-0">•</span>
                <span>Техосмотр, ОСАГО и регламентное обслуживание парка</span>
              </li>
              <li class="flex gap-3">
                <span class="font-bold text-blue-600 shrink-0">•</span>
                <span>Поддержка пользователей</span>
              </li>
            </ul>
          </div>
        </div>
      </section>

      <section id="services" class="px-6 max-[767px]:px-4 max-[479px]:px-3 py-12 max-[767px]:py-8 max-[479px]:py-6">
        <div class="max-w-6xl mx-auto">
          <h2 class="text-3xl max-[991px]:text-2xl max-[479px]:text-xl font-bold pb-2 text-gray-900">Услуги и тарифы</h2>
          <p class="text-gray-700 pb-8 max-w-3xl text-lg max-[767px]:text-base leading-relaxed">
            Выберите формат аренды под задачу: минутная оплата в городе, почасовой пакет для деловых поездок или сутки для поездок за город.
          </p>
          <div class="grid md:grid-cols-3 gap-6">
            <article
              v-for="item in services"
              :key="item.title"
              class="bg-white border border-solid border-gray-300 rounded shadow-sm p-6 transition ease-in-out hover:shadow-md"
            >
              <h3 class="text-xl font-bold text-gray-900 pb-3">{{ item.title }}</h3>
              <p class="text-gray-700 leading-relaxed">{{ item.text }}</p>
            </article>
          </div>
        </div>
      </section>

      <section id="team" class="px-6 max-[767px]:px-4 max-[479px]:px-3 py-12 max-[767px]:py-8 max-[479px]:py-6 bg-white border-t border-solid border-gray-200">
        <div class="max-w-6xl mx-auto">
          <h2 class="text-3xl max-[991px]:text-2xl max-[479px]:text-xl font-bold pb-2 text-gray-900">Команда</h2>
          <p class="text-gray-700 pb-8 max-w-3xl text-lg max-[767px]:text-base leading-relaxed">
            За CarShare стоят специалисты по операциям, IT, клиентскому сервису и безопасности парка.
          </p>
          <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <article
              v-for="person in team"
              :key="person.name"
              class="border border-solid border-gray-300 rounded p-6 bg-gray-50 text-center"
            >
              <img
                :src="person.photoUrl"
                :alt="person.name"
                class="w-16 h-16 mx-auto mb-4 rounded-full object-cover border border-gray-200"
                width="64"
                height="64"
                loading="lazy"
                decoding="async"
              />
              <h3 class="text-lg font-bold text-gray-900">{{ person.name }}</h3>
              <p class="text-sm text-gray-600 pt-1">{{ person.role }}</p>
            </article>
          </div>
        </div>
      </section>

      <section class="px-6 max-[767px]:px-4 max-[479px]:px-3 py-12 max-[767px]:py-8 max-[479px]:py-6" aria-labelledby="seo-heading">
        <div class="max-w-6xl mx-auto text-gray-700">
          <h2 id="seo-heading" class="text-3xl max-[991px]:text-2xl max-[479px]:text-xl font-bold text-gray-900 pb-4">
            Полезная информация для пользователей и поисковых систем
          </h2>
          <p class="pb-4 leading-relaxed">
            Каршеринг CarShare — это вид мобильности, при котором автомобиль арендуется через приложение или веб-интерфейс на короткий срок.
            Сервис подходит тем, кому не нужен личный автомобиль каждый день: поездки на встречи, выезд за покупками, транспорт для гостей города.
          </p>
          <h3 class="text-xl font-bold text-gray-900 pt-4 pb-2">Как работает сервис</h3>
          <p class="pb-4 leading-relaxed">
            Регистрация, верификация данных, привязка способа оплаты и выбор автомобиля на карте. После завершения поездки стоимость рассчитывается автоматически по тарифу и времени использования.
            Документы и условия использования доступны до начала аренды.
          </p>
          <h3 class="text-xl font-bold text-gray-900 pt-4 pb-2">Безопасность и конфиденциальность</h3>
          <p class="leading-relaxed">
            Мы обрабатываем персональные данные в соответствии с применимым законодательством и политикой конфиденциальности.
            Техническая безопасность аккаунта — через устойчивые пароли и рекомендации по защите устройства.
          </p>
        </div>
      </section>

      <section id="contacts" class="px-6 max-[767px]:px-4 max-[479px]:px-3 py-12 max-[767px]:py-8 max-[479px]:py-6 bg-white border-t border-solid border-gray-200">
        <div class="max-w-6xl mx-auto">
          <h2 class="text-3xl max-[991px]:text-2xl max-[479px]:text-xl font-bold pb-6 text-gray-900">Контакты</h2>
          <div class="grid md:grid-cols-2 gap-8 max-[767px]:gap-5">
            <address class="not-italic text-gray-700 leading-relaxed text-lg max-[767px]:text-base">
              <p class="font-bold text-gray-900 pb-2">ООО «КарШер Плюс»</p>
              <p>Юридический адрес: г. Пермь, ул. Луначарского, д. 24</p>
              <p class="pt-2">
                Email:
                <a href="mailto:pleskovd96@gmail.com" class="text-blue-600 hover:text-blue-700">pleskovd96@gmail.com</a>
              </p>
              <p>
                Телефон:
                <a href="tel:+79526588289" class="text-blue-600 hover:text-blue-700">+7 (952) 658-82-89</a>
              </p>
            </address>
            <div class="text-gray-700 leading-relaxed text-lg max-[767px]:text-base">
              <p class="pb-4">
                Время работы службы поддержки: ежедневно с 08:00 до 22:00 по московскому времени.
              </p>
              <router-link
                to="/register"
                class="inline-block px-7 py-3 bg-blue-600 text-white font-medium text-sm leading-snug uppercase rounded shadow-md hover:bg-blue-700 transition duration-150 ease-in-out"
              >
                Зарегистрироваться
              </router-link>
            </div>
          </div>
        </div>
      </section>
    </main>

    <footer class="px-6 max-[767px]:px-4 max-[479px]:px-3 py-8 max-[767px]:py-6 border-t border-solid border-gray-200 bg-gray-100 text-sm text-gray-600">
      <div class="max-w-6xl mx-auto flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <p class="m-0">© {{ year }} CarShare. Все права защищены.</p>
        <div class="flex flex-wrap gap-4">
          <router-link to="/login" class="text-gray-700 hover:text-blue-600">Вход</router-link>
          <router-link to="/register" class="text-gray-700 hover:text-blue-600">Регистрация</router-link>
        </div>
      </div>
    </footer>
  </body>
</template>

<script setup>
import { computed, onMounted } from 'vue'

const year = computed(() => new Date().getFullYear())

const services = [
  {
    title: 'Минутный тариф',
    text: 'Оплата по времени и пробегу в городе. Удобно для коротких поездок и мультимодальных маршрутов.',
  },
  {
    title: 'Почасовые пакеты',
    text: 'Зафиксированный пакет часов для встреч и дел без сюрпризов в чеке.',
  },
  {
    title: 'Суточная аренда',
    text: 'Автомобиль на целый день или выходные — для поездок за город и длительных маршрутов.',
  },
]

const team = [
  {
    name: 'Анна Смирнова',
    role: 'Руководитель операций',
    photoUrl: 'https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?q=80&w=300&auto=format&fit=crop',
  },
  {
    name: 'Дмитрий Орлов',
    role: 'CTO, продукт и платформа',
    photoUrl: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=300&auto=format&fit=crop',
  },
  {
    name: 'Елена Козлова',
    role: 'Клиентский сервис',
    photoUrl: 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?q=80&w=300&auto=format&fit=crop',
  },
  {
    name: 'Иван Петров',
    role: 'Безопасность парка',
    photoUrl: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=300&auto=format&fit=crop',
  },
]

onMounted(() => {
  document.title = 'CarShare — аренда автомобилей онлайн'
  let meta = document.querySelector('meta[name="description"]')
  if (!meta) {
    meta = document.createElement('meta')
    meta.setAttribute('name', 'description')
    document.head.appendChild(meta)
  }
  meta.setAttribute(
    'content',
    'Главная страница CarShare: описание сервиса, тарифы аренды, команда и контакты компании.',
  )
})
</script>
