<template>
    <transition
        enter-active-class="duration-1000 ease-in"
        enter-from-class="translate-x-[-200%]"
        enter-to-class="translate-x-[10%]"
        leave-active-class="duration-1000 ease-out"
        leave-from-class="translate-x-[10%]"
        leave-to-class="translate-x-[-200%]"
    >
        <slot />
    </transition>
</template>
<script setup>
</script>
