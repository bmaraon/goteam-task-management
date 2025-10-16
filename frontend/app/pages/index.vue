<script setup lang="ts">
definePageMeta({
    layout: false
})

import { navigateTo } from '#app'

// Redirect "/" to "/login"
navigateTo('/login')
</script>