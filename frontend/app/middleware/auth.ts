import { defineNuxtRouteMiddleware, navigateTo } from '#app'
import type { RouteLocationNormalized } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import type { User } from '../stores/auth'

export default defineNuxtRouteMiddleware(async (to: RouteLocationNormalized) => {
  const authStore = useAuthStore()
  const user: User | null = await authStore.fetchUser()

  // If still no user → redirect to login
  if (!user && to.path !== '/login') {
    return navigateTo('/login')
  }

  if (user && to.path === '/login') {
    return navigateTo('/dashboard')
  }
})