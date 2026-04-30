<template>
    <body>
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
    <section class="min-h-screen py-8 max-[767px]:py-6 max-[479px]:py-4 flex items-center">
      <div class="px-6 max-[767px]:px-4 max-[479px]:px-3 w-full text-gray-800 flex justify-center">
        <div class="flex justify-between items-center lg:flex-nowrap flex-wrap w-full max-w-6xl mx-auto gap-6">
            <div class="relative overflow-hidden rounded-2xl bg-slate-900 shadow-xl ring-1 ring-slate-200/70 grow-0 shrink-1 md:shrink-0 basis-auto xl:w-6/12 lg:w-6/12 md:w-9/12 max-[991px]:hidden">
                <img
                    src="https://images.unsplash.com/photo-1555215695-3004980ad54e?q=80&w=1170&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                    class="block w-full object-cover"
                    alt="Современный автомобиль в городской среде"
                />
                <div class="pointer-events-none absolute inset-0 bg-gradient-to-t from-slate-900/45 via-slate-900/10 to-transparent"></div>
            </div>
            <div class="xl:ml-20 xl:w-5/12 lg:w-5/12 md:w-8/12 w-full max-w-xl">
              <h2 class="pb-6 text-3xl max-[991px]:text-2xl max-[479px]:text-xl font-bold">Авторизация</h2>
              <form @submit.prevent="onSubmit">
                  <div class="mb-6">
                      <input
                          type="email"
                          class="form-control block w-full px-4 py-2 max-[479px]:px-3 max-[479px]:py-2 text-xl max-[991px]:text-lg max-[479px]:text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                          id="email"
                          name="email"
                          placeholder="Email"
                          autocomplete="email"
                          inputmode="email"
                          v-model="form.email"
                      />
                  </div>    
                  <div class="mb-6">
                      <input
                          type="password"
                          class="form-control block w-full px-4 py-2 max-[479px]:px-3 max-[479px]:py-2 text-xl max-[991px]:text-lg max-[479px]:text-base font-normal text-gray-700 bg-white bg-clip-padding border border-solid border-gray-300 rounded transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none"
                          id="password"
                          name="password"
                          placeholder="Пароль"
                          v-model="form.password"
                      />
                  </div>  
                  <div class="text-center lg:text-left">
                      <button 
                            type="submit" 
                            class="inline-block w-full max-[991px]:w-full lg:w-auto px-7 py-3 bg-blue-600 text-white font-medium text-sm leading-snug uppercase rounded shadow-md hover:bg-blue-700 hover:shadow-lg focus:bg-blue-700 focus:shadow-lg focus:outline-none focus:ring-0 active:bg-blue-800 active:shadow-lg transition duration-150 ease-in-out">
                            Войти
                      </button>
                      <p v-if="errorMessage" class="text-sm text-red-600 mt-3">{{ errorMessage }}</p>
                      <p class="text-sm font-semibold mt-2 pt-1 mb-0">
                            Нет аккаунта?
                            <router-link to="/register" class="text-red-600 hover:text-red-700 transition duration-200 ease-in-out">Зарегистрироваться</router-link>
                      </p>
                  </div>
              </form>
            </div>
        </div>
      </div>
    </section>
  </body>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue'
import { useRouter } from 'vue-router'
import { api, setAuthToken } from '../../lib/api'

const router = useRouter()
const errorMessage = ref('')
const form = reactive({
  email: '',
  password: '',
})

async function onSubmit() {
  errorMessage.value = ''
  try {
    const response = await api.post('/auth/login', form)
    const token = response?.data?.data?.token
    const role = response?.data?.data?.user?.role
    if (!token) throw new Error('Token not received')
    setAuthToken(token)
    await router.push(role === 'admin' ? '/admin' : '/app')
  } catch (error) {
    errorMessage.value = error?.response?.data?.message || 'Не удалось войти. Проверьте email и пароль.'
  }
}

onMounted(() => {
  document.title = 'Вход в аккаунт CarShare'
  let meta = document.querySelector('meta[name="description"]')
  if (!meta) {
    meta = document.createElement('meta')
    meta.setAttribute('name', 'description')
    document.head.appendChild(meta)
  }
  meta.setAttribute('content', 'Страница входа в личный кабинет CarShare для клиентов и администраторов.')
})
</script>