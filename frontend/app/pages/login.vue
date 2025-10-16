<template>
    <div class="min-h-screen flex flex-col items-center justify-center bg-gray-100 space-y-6">

        <!-- Company Logo at the top -->
        <div class="flex justify-center">
            <img src="/logo.jpg" alt="Company Logo" class="h-20 w-auto">
        </div>

        <!-- Login Form Card -->
        <div class="bg-white shadow-md rounded-lg w-full max-w-md p-8">
            <!-- Header -->
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-2">Sign In</h2>
            <p class="text-sm text-gray-500 text-center mb-6">
                Login to continue using the app
            </p>

            <!-- Login Form -->
            <form class="space-y-4" @submit.prevent="handleLogin">
                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <div class="relative">
                        <input id="email" v-model="email" type="email" placeholder="Enter your email"
                            class="w-full border border-gray-300 rounded-md pl-10 pr-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <Mail class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" size="20" />
                    </div>
                </div>

                <!-- Password Field -->
                <div>
                    <div class="flex justify-between items-center mb-1">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <span class="text-xs text-blue-500 cursor-pointer">Forgot your password?</span>
                    </div>
                    <div class="relative">
                        <input id="password" v-model="password" :type="showPassword ? 'text' : 'password'"
                            placeholder="Enter your password"
                            class="w-full border border-gray-300 rounded-md pl-10 pr-10 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                            required>
                        <Lock class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" size="20" />
                        <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400"
                            @click="togglePassword">
                            <component :is="showPassword ? Eye : EyeOff" size="20" />
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                        class="w-full bg-blue-500 text-white py-2 rounded-md font-medium hover:bg-blue-600 transition-colors">
                        Login
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup lang="ts">
definePageMeta({
    middleware: 'auth',
    layout: false
})

import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore, type LoginPayload } from '../stores/auth'
import { Mail, Lock, Eye, EyeOff } from 'lucide-vue-next'

const email = ref<string>('')
const password = ref<string>('')
const showPassword = ref<boolean>(false)

const router = useRouter()
const authStore = useAuthStore()

const togglePassword = (): void => {
    showPassword.value = !showPassword.value
}

const handleLogin = async (): Promise<void> => {
    const payload: LoginPayload = {
        email: email.value,
        password: password.value,
    }

    try {
        const res = await authStore.login(payload)

        if (res.token) {
            router.push('/dashboard')
        } else {
            alert('Invalid credentials')
        }
    } catch (error) {
        console.error('Login failed:', error)
        alert('Something went wrong. Please try again.')
    }
}
</script>