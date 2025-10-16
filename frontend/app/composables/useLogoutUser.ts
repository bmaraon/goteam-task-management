import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { useScheduleStore } from '../stores/schedule'
import { useTaskStore } from '../stores/task'

export const useLogoutUser = () => {
    const router = useRouter()
    const authStore = useAuthStore()
    const scheduleStore = useScheduleStore()
    const taskStore = useTaskStore()

    const logout = async (): Promise<void> => {
        try {
            await authStore.logout()
        } catch (error) {
            console.error('Logout failed:', error)
        } finally {
            authStore.resetStates()
            scheduleStore.resetStates()
            taskStore.resetStates()
            router.push({ name: 'login' })
        }
    }

    return { logout }
}