<script setup lang="ts">
definePageMeta({
    layout: false
})

import { navigateTo } from 'nuxt/app'

navigateTo({ name: 'login' })
</script>