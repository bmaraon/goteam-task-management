<template>
    <div class="flex justify-center items-center h-screen">
        <p class="text-lg">Logging out...</p>
    </div>
</template>

<script setup lang="ts">
import { onMounted } from "vue"
import { useRouter } from "vue-router"
import { useAuthStore } from "../stores/auth"

const authStore = useAuthStore()
const router = useRouter()

onMounted(async () => {
    try {
        await authStore.logout()
        router.push({ name: "login" })
    } catch (error) {
        console.error("Logout failed:", error)
        router.push({ name: "login" })
    }
})
</script>