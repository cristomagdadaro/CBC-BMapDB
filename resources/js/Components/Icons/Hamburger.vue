<script setup>
defineProps({
    showMenu: Boolean,
})
</script>
<template>
    <svg
        class="h-6 w-6"
        stroke="currentColor"
        fill="none"
        viewBox="0 0 24 24"
    >
        <path
            :class="{'hidden': showMenu, 'inline-flex': ! showMenu }"
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M4 6h16M4 12h16M4 18h16"
        />
        <path
            :class="{'hidden': ! showMenu, 'inline-flex': showMenu }"
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
            d="M6 18L18 6M6 6l12 12"
        />
    </svg>
</template>
