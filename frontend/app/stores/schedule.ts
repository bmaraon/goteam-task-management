import { defineStore } from 'pinia'
import { Ref, ref } from 'vue'

export const useScheduleStore = defineStore('schedule', () => {
    // -------------------
    // State
    // -------------------
    const selectedDate: Ref<'today' | 'yesterday' | Date | ''> = ref('')
    const formattedSelectedDate: Ref<string> = ref('')

    // -------------------
    // Actions
    // -------------------
    const resetStates = (): void => {
        selectedDate.value = ''
        formattedSelectedDate.value = ''
    }
    
    const setSelectedDate = (date: 'today' | 'yesterday' | Date): void => {
        selectedDate.value = date
        formattedSelectedDate.value = formatDate(date)
    }

    const formatDate = (date: 'today' | 'yesterday' | Date): string => {
        let d: Date

        if (date === 'today') {
            d = new Date()
        } else if (date === 'yesterday') {
            d = new Date()
            d.setDate(d.getDate() - 1)
        } else {
            d = date
        }
    
        // Format as YYYY-MM-DD for Laravel
        const year = d.getFullYear()
        const month = String(d.getMonth() + 1).padStart(2, '0')
        const day = String(d.getDate()).padStart(2, '0')
        
        return `${year}-${month}-${day}`
    }

    // -------------------
    // Return store
    // -------------------
    return {
        selectedDate,
        formatDate,
        formattedSelectedDate,
        setSelectedDate,
        resetStates
    }
})