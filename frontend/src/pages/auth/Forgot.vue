<template>
    
</template>

<script setup>
import { onMounted } from 'vue'

onMounted(() => {
  document.title = 'Восстановление пароля CarShare'
  let meta = document.querySelector('meta[name="description"]')
  if (!meta) {
    meta = document.createElement('meta')
    meta.setAttribute('name', 'description')
    document.head.appendChild(meta)
  }
  meta.setAttribute('content', 'Страница восстановления доступа к аккаунту в сервисе CarShare.')
})
</script>