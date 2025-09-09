import { defineStore } from 'pinia'
import { ref, Ref } from 'vue'
import { format } from 'date-fns'

export interface Task {
  id?: number
  task: string
  is_completed: number | boolean
  priority: number
  scheduled_at: string
  created_at?: string
  updated_at?: string
}

export const useTaskStore = defineStore('taskStore', () => {
    // -------------------
    // State
    // -------------------
    const defaultFilterValues: Ref<object> = ref({
        search: '',
        date: format((new Date()), 'yyyy-MM-dd')
    })
    const filters: Ref<object> = ref(
        typeof window !== 'undefined'
            ? JSON.parse(localStorage.getItem('filters') ?? JSON.stringify(defaultFilterValues.value))
            : defaultFilterValues.value
    )
    const tasks: Ref<Task[]> = ref([])
    const loading: Ref<boolean> = ref(false)
    const maxPriority: Ref<number> = ref(0)
    const isChangingPriorities: Ref<boolean> = ref(false)
    const error: Ref<string | null> = ref(null)

    // -------------------
    // Actions
    // -------------------
    const setFilters = (filter: object = {}) => {
        filters.value = { ...filters.value, ...filter }

        if (typeof window !== 'undefined') {
            localStorage.setItem('filters', JSON.stringify(filters.value))
        }
    }

    const setTasks = (newTasks: Task[] = []) => {
        tasks.value = [ ...newTasks ]
    }

    const setMaxPriority = (max: number = 0) => {
        maxPriority.value = max
    }

    const setChangingPriorities = (flag: boolean = false) => {
        isChangingPriorities.value = flag
    }

    const fetchTasks = async (): Promise<void> => {
        const { $api } = useNuxtApp()
        
        loading.value = true
        error.value = null

        try {
            const response = await $api.get('/api/tasks', { params: { ...filters.value } })
            tasks.value = [ ...response.data.data ]
            maxPriority.value = response.data.meta.max_priority
        } catch (err: any) {
            error.value = err.response?.data?.message || err.message
        } finally {
            loading.value = false
        }
    }

    const fetchTask = async (id: number): Promise<void>  => {
        const { $api } = useNuxtApp()

        loading.value = true
        error.value = null

        try {
            const response = await $api.get(`/api/tasks/${id}`)
            return response.data
        } catch (err: any) {
            error.value = err.response?.data?.message || err.message
        } finally {
            loading.value = false
        }
    }

    const createTask = async (task: Task): Promise<void> => {
        const { $api } = useNuxtApp()

        loading.value = true
        error.value = null

        try {
            const response = await $api.post('/api/tasks', task)
            tasks.value = [ ...tasks.value, response.data.data ]
            maxPriority.value = maxPriority.value + 1
        } catch (err: any) {
            error.value = err.response?.data?.message || err.message
        } finally {
            loading.value = false
        }
    }

    const updateTask = async (task: Task): Promise<void>  => {
        const { $api } = useNuxtApp()

        loading.value = true
        error.value = null

        try {
            const response = await $api.put(`/api/tasks/${task.id}`, task)

            if (!isChangingPriorities) {
                const index = tasks.value.findIndex(t => t.id === task.id)
                if (index !== -1) tasks.value[index] = { ...tasks.value[index], ...response.data.data }
            }
        } catch (err: any) {
            error.value = err.response?.data?.message || err.message
        } finally {
            loading.value = false
        }
    }

    const deleteTask = async (task: Task): Promise<Task[] | void>  => {
        const { $api } = useNuxtApp()

        loading.value = true
        error.value = null

        try {
            await $api.delete(`/api/tasks/${task.id}`)
            await fetchTasks()
        } catch (err: any) {
            error.value = err.response?.data?.message || err.message
        } finally {
            loading.value = false
        }
    }

    // -------------------
    // Return store
    // -------------------
    return {
        maxPriority,
        setFilters,
        tasks,
        setTasks,
        loading,
        error,
        filters,
        fetchTasks,
        fetchTask,
        createTask,
        updateTask,
        deleteTask,
        setChangingPriorities,
        isChangingPriorities
    }
})