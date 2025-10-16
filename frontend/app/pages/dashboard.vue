<template>
    <section class="max-w-3xl mx-auto bg-transparent rounded-lg p-4 relative flex flex-col h-full">
        <!-- When tasks empty: show textarea on top -->
        <div v-if="tasks.length === 0" class="flex-1 flex flex-col">
            <label class="block text-sm text-slate-600 mb-2">What's on your mind?</label>
            <textarea v-model="newTask" rows="2" placeholder="Add a new task..."
                class="w-full rounded-md border border-slate-200 p-2 resize-none focus:outline-none focus:ring-2 focus:ring-slate-200"
                @keydown.enter.prevent="addTask"></textarea>
        </div>

        <!-- When tasks exist: list + pinned input -->
        <div v-else class="flex-1 flex flex-col">

            <!-- Scrollable task list -->
            <div class="flex-1 overflow-auto pr-1">
                <draggable :list="tasksCopy" @change="onOrderChange" item-key="priority"
                    :disabled="tasksFilters?.search !== ''">
                    <template #item="{ element: item, index }">
                        <div class="flex items-center gap-3 p-4 bg-white rounded-lg border border-slate-200 shadow-sm outline outline-1 outline-slate-100 mb-3 last:mb-0 transition-all duration-200"
                            :class="{ 'opacity-60 line-through': item.is_completed }">
                            <!-- Checkbox -->
                            <div class="flex-shrink-0">
                                <input class="cursor-pointer" type="checkbox" :checked="item.is_completed"
                                    :disabled="editingPriority === item.priority" @change="toggleComplete(item)" />
                            </div>

                            <!-- Task details / edit -->
                            <div class="flex-1">
                                <div v-if="editingPriority !== item.priority"
                                    @dblclick="!item.is_completed ? enterEditTask(item) : null" class="cursor-text">
                                    <div class="font-medium">{{ item.task }}</div>
                                </div>

                                <div v-else>
                                    <input ref="editInput" v-model="editTask" @keydown.esc="cancelEdit()"
                                        @keydown.enter="submitEdit(item)"
                                        class="w-full rounded-md border border-slate-200 p-2 focus:outline-none focus:ring-2 focus:ring-slate-300" />
                                </div>
                            </div>

                            <!-- Trash icon -->
                            <div class="flex-shrink-0">
                                <button @click="removeTask(item)" title="Delete">
                                    <Trash2 :size="18" />
                                </button>
                            </div>
                        </div>
                    </template>
                </draggable>
            </div>

            <!-- Pinned input at bottom -->
            <div class="mt-3 border-t pt-3 flex flex-col">
                <label class="block text-sm text-slate-600 mb-2">What's on your mind?</label>
                <textarea v-model="newTask" rows="2" placeholder="Add a new task..."
                    class="w-full rounded-md border border-slate-200 p-2 resize-none focus:outline-none focus:ring-2 focus:ring-slate-200"
                    @keydown.enter.prevent="addTask"></textarea>
            </div>
        </div>
    </section>
</template>

<script setup lang="ts">
definePageMeta({
    middleware: 'auth',
    name: 'dashboard'
})

import { Ref, ref, onMounted, computed, watch, nextTick } from 'vue'
import { useTaskStore, Task } from '../stores/task'
import { useScheduleStore } from '../stores/schedule'
import { cloneDeep } from 'lodash'

import Draggable from 'vuedraggable'
import * as lucide from 'lucide-vue-next'

const { Trash2 } = lucide as any

const scheduleStore = useScheduleStore()
const taskStore = useTaskStore()

const tasksCopy: Ref<Task[]> = ref([])
const newTask: Ref<string> = ref('')
const editingPriority: Ref<number> = ref(0)
const editInput: Ref<HTMLInputElement | null> = ref(null)
const editTask: Ref<string> = ref('')

const formattedSelectedDate = computed(() => scheduleStore.formattedSelectedDate)
const isChangingPriorities = computed(() => taskStore.isChangingPriorities)
const tasksFilters = computed(() => taskStore.filters)
const maxPriority = computed(() => taskStore.maxPriority)
const tasks = computed(() => taskStore.tasks)

onMounted(() => {
    scheduleStore.setSelectedDate(new Date(tasksFilters?.value.date))
})

watch(formattedSelectedDate, (newDate, oldDate) => {
    if (oldDate !== newDate) {
        taskStore.setFilters({ date: newDate })
    }
})

watch(tasksFilters, async (newFilters, oldFilters) => {
    if (oldFilters !== newFilters) {
        taskStore.fetchTasks()
    }
})

watch(tasks, (newTasks, oldTasks) => {
    if (!isChangingPriorities.value) {
        tasksCopy.value = cloneDeep(newTasks)
    }
})

const addTask = (): void => {
    const task = newTask.value.trim()
    if (!task) return

    taskStore.createTask({
        priority: maxPriority.value + 1,
        task,
        is_completed: 0,
        scheduled_at: formattedSelectedDate.value
    })

    newTask.value = ''
}

const removeTask = (task: Task): void => {
    taskStore.deleteTask(task)
}

const enterEditTask = (task: Task): void => {
    editingPriority.value = task.priority
    editTask.value = task.task

    nextTick(() => {
        if (editInput.value) editInput.value.select()
    })
}

const cancelEdit = (): void => {
    editingPriority.value = 0
    editTask.value = ''
}

const submitEdit = (task: Task): void => {
    task.task = editTask.value.trim()
    task.is_completed = task.is_completed ? 1 : 0

    taskStore.updateTask(task)

    cancelEdit()
}

const toggleComplete = (task: Task): void => {
    const isCompleted = !task.is_completed
    task.is_completed = isCompleted ? 1 : 0

    taskStore.updateTask(task)
}

const onOrderChange = async (): Promise<void> => {
    taskStore.setChangingPriorities(true)
    tasksCopy.value.map(async (item, index) => {
        const taskInIndex = tasks.value[index];

        if (taskInIndex.id !== item.id) {
            item.priority = index + 1
            item.is_completed = item.is_completed ? 1 : 0
            await taskStore.updateTask(item)
        }
    })

    taskStore.setChangingPriorities(false)
    taskStore.setTasks(cloneDeep(tasksCopy.value))
}
</script>