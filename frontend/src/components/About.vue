<template>
    <h1>About</h1>
</template>

<script setup>
import { onMounted } from 'vue'

onMounted(() => {
  document.title = 'О проекте CarShare'
  let meta = document.querySelector('meta[name="description"]')
  if (!meta) {
    meta = document.createElement('meta')
    meta.setAttribute('name', 'description')
    document.head.appendChild(meta)
  }
  meta.setAttribute('content', 'Страница с базовой информацией о проекте CarShare.')
})
</script>