import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

export const useLogoutUser = () => {
    const router = useRouter()
    const authStore = useAuthStore()

    const logout = async (): Promise<void> => {
        try {
            await authStore.logout()
        } catch (error) {
            console.error('Logout failed:', error)
        } finally {
            router.push({ name: 'login' })
        }
    }

    return { logout }
}