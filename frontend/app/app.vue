<script setup lang="ts">
import { useRouter } from 'vue-router'
import { onMounted } from 'vue'

const router = useRouter()

onMounted(() => {
  // Example: check auth (replace with your auth logic)
  const isLoggedIn = false
  if (!isLoggedIn) {
    router.push('/login')
  }
})
</script>

<template>
  <NuxtLayout>
    <NuxtPage />
  </NuxtLayout>
</template>