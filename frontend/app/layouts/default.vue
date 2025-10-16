<template>
    <div class="min-h-screen bg-slate-50">
        <!-- Fixed Header -->
        <header class="fixed top-0 left-0 right-0 h-14 bg-white shadow-sm z-30 flex items-center px-4">
            <div class="flex items-center gap-4 w-full">
                <!-- Logo -->
                <div class="flex items-center gap-3 w-48">
                    <img src="/logo.jpg" alt="logo" class="h-8 w-8" />
                    <h1 class="text-lg font-semibold">MyDashboard</h1>
                </div>

                <!-- Search bar (center) -->
                <div class="flex-1 max-w-xl pl-10">
                    <input v-model="search" type="search" placeholder="Search..."
                        class="w-full rounded-md border border-slate-200 px-3 py-2 focus:outline-none focus:ring-2 focus:ring-slate-300" />
                </div>

                <!-- Greeting -->
                <div class="ml-auto">
                    <span class="text-sm"><strong>Hello, {{ user?.name }}!</strong></span> | <button
                        class="text-sm no-underline text-gray" @click="logout">Logout</button>
                </div>
            </div>
        </header>

        <!-- Layout: sidebar + main -->
        <div class="pt-14 flex">
            <!-- Fixed Sidebar -->
            <Sidebar />

            <!-- Spacer to account for fixed sidebar -->
            <div class="w-64" />

            <!-- Main content -->
            <main class="flex-1 p-6 min-h-[calc(100vh-56px)] relative">
                <slot />
            </main>
        </div>
    </div>
</template>

<script setup lang="ts">
import { Ref, ref, onMounted, computed, watch, nextTick } from 'vue'
import { useRouter, NavigationFailure } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { useTaskStore } from '../stores/task'
import { useLogoutUser } from '../composables/useLogoutUser'

import Sidebar from '../components/Sidebar.vue'

const taskStore = useTaskStore()
const authStore = useAuthStore()
const { logout } = useLogoutUser()
const user = computed(() => authStore.user)
const taskFilters = computed(() => taskStore.filters)

const search: Ref<string> = ref(taskFilters.value?.search)

watch(search, newSearch => {
    taskStore.setFilters({ search: newSearch })
})
</script>