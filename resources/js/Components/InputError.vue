<script setup>
import {ref} from "vue";

defineProps({
    message: String,
});

const show = ref(false);
const toggleShow = () => {
    show.value = !show.value;
    if (show.value) {
        setTimeout(() => {
            show.value = false;
        }, 5000);
    }
};
</script>

<template>
    <div v-show="message" class="flex items-center gap-0.5 overflow-hidden">
        <transition name="slide-fade">
            <p v-show="show" class="text-xs text-red-600">
                {{ message }}
            </p>
        </transition>
        <span @mouseenter="toggleShow" @click="toggleShow"  class="rounded-full border px-[0.4rem] text-xs scale-[80%] select-none text-red-500 border-red-500" :title="message">!</span>
    </div>
</template>

<style scoped>
.slide-fade-enter-active, .slide-fade-leave-active {
    transition: transform 0.5s ease-out, opacity 0.5s ease-out;
}
.slide-fade-enter-from, .slide-fade-leave-to {
    transform: translateX(100%);
    opacity: 0;
}
.slide-fade-enter-to, .slide-fade-leave-from {
    transform: translateX(0%);
    opacity: 1;
}
</style>
