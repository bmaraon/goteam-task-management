<template>
    <aside class="w-70 fixed left-0 top-14 bottom-0 bg-white border-r overflow-auto z-20">
        <nav class="p-4">
            <ul class="space-y-2">
                <!-- Today -->
                <li>
                    <button @click="setRange('today')" :class="menuClass('today')"><span
                            class="text-sm">Today</span></button>
                </li>

                <!-- Yesterday -->
                <li>
                    <button @click="setRange('yesterday')" :class="menuClass('yesterday')"><span
                            class="text-sm">Yesterday</span></button>
                </li>

                <!-- Remaining days of this week -->
                <li v-for="date in remainingThisWeek" :key="date.getTime()">
                    <button @click="setRange(date)" :class="menuClass(date)">
                        <span class="text-sm">{{ formatDate(date) }}</span>
                    </button>
                </li>

                <!-- Last week divider -->
                <li class="mt-4 mb-2 text-black font-bold">Last week</li>

                <!-- Last week dates -->
                <li v-for="date in lastWeekDates" :key="date.getTime()">
                    <button @click="setRange(date)" :class="menuClass(date)">
                        <span class="text-sm">{{ formatDate(date) }}</span>
                    </button>
                </li>
            </ul>
        </nav>
    </aside>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useTaskStore, Task } from '../stores/task'
import { useScheduleStore } from '../stores/schedule'
import { format, subDays, startOfWeek, endOfWeek } from 'date-fns'

const scheduleStore = useScheduleStore()
const taskStore = useTaskStore()

const today = new Date()
const yesterday = subDays(today, 1)

const taskFilters = computed(() => taskStore.filters)

const remainingThisWeek = computed(() => {
    const start = startOfWeek(today)
    const end = subDays(yesterday, 0)
    const dates: Date[] = []
    for (let d = end; d >= start; d = subDays(d, 1)) {
        if (d.getTime() !== yesterday.getTime()) {
            dates.push(d)
        }
    }
    return dates
})

const lastWeekDates = computed(() => {
    const start = startOfWeek(subDays(today, 7))
    const end = endOfWeek(subDays(today, 7))
    const dates: Date[] = []
    for (let d = end; d >= start; d = subDays(d, 1)) {
        dates.push(d)
    }
    return dates
})

const setRange = (date: 'today' | 'yesterday' | Date) => {
    scheduleStore.setSelectedDate(date)
}

const menuClass = (date: 'today' | 'yesterday' | Date) => {
    const selected = scheduleStore.selectedDate

    if (
        (date === 'today' && selected === 'today') ||
        (date === 'yesterday' && selected === 'yesterday') ||
        (date instanceof Date && selected instanceof Date && selected.getTime() === date.getTime()) ||
        (taskFilters.value?.date === scheduleStore.formatDate(date))
    ) {
        return 'bg-blue-100 text-blue-700 w-full text-left px-2 py-1 rounded'
    }

    return 'w-full text-left px-2 py-1 hover:bg-gray-100 rounded'
}

const formatDate = (date: Date) => format(date, 'EEEE, MMMM d, yyyy')
</script>