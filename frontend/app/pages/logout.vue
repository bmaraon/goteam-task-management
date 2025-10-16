<template>
    <div class="flex justify-center items-center h-screen">
        <p class="text-lg">Logging out...</p>
    </div>
</template>

<script setup lang="ts">
definePageMeta({
    name: 'logout',
    middleware: 'auth',
    layout: false
})

import { onMounted } from "vue"
import { useLogoutUser } from "../composables/useLogoutUser"

const { logout } = useLogoutUser()

onMounted(async () => {
    await logout()
})
</script>