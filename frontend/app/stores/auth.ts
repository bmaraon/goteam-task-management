import { defineStore } from 'pinia'
import { Ref, ref } from 'vue'

export interface User {
  id: number
  name: string
  email: string
}

export interface LoginPayload {
  email: string
  password: string
}

interface LoginResponse {
  token: string
  user: User
}

export const useAuthStore = defineStore('auth', () => {
  // -------------------
  // State
  // -------------------
  const user: Ref<User | null> = ref(null)
  const token: Ref<string | null> = ref(null)

  // -------------------
  // Actions
  // -------------------
  const login = async (payload: LoginPayload): Promise<string> => {
    const { $api } = useNuxtApp()

    await $api.get('/sanctum/csrf-cookie')

    const res = await $api.post<LoginResponse>('/api/login', payload, { withCredentials: true })

    token.value = res.data.token

    if (typeof window !== 'undefined') {
      localStorage.setItem('token', token.value)
    }

    await fetchUser()

    return res.data
  }

  const fetchUser = async (): Promise<User | null> => {
    const { $api } = useNuxtApp()

    try {
      const response = await $api.get<User>('/api/user')
      user.value = response.data
      return user.value
    } catch (error) {
      user.value = null
      return null
    }
  }

  const logout = async (): Promise<void> => {
    const { $api } = useNuxtApp()

    try {
      await $api.delete('/api/logout')
    } catch (error) {
      console.error('Logging out user failed:', error)
    } finally {
      user.value = null
      token.value = null
      
      if (typeof window !== 'undefined') {
        localStorage.removeItem('token')
        localStorage.removeItem('filters')
      }
    }
  }

  // -------------------
  // Initialize token from localStorage on client
  // -------------------
  if (process.client) {
    token.value = localStorage.getItem('token')
  }

  // -------------------
  // Return store
  // -------------------
  return { user, token, login, fetchUser, logout }
})