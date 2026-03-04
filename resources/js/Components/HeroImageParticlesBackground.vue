<script setup>
import { computed, onBeforeUnmount, onMounted, ref } from 'vue';
import ParticlesBackground from '@/Components/ParticlesBackground.vue';

const props = defineProps({
    images: {
        type: Array,
        default: () => [
            '/img/carousel/image-1.jpg',
            '/img/carousel/image-2.jpg',
            '/img/carousel/image-3.jpg',
            '/img/carousel/image-4.jpg',
        ],
    },
    intervalMs: {
        type: Number,
        default: 10000,
    },
    showParticles: {
        type: Boolean,
        default: true,
    },
    overlayClass: {
        type: String,
        default: 'bg-gradient-to-b from-cbc-dark-green/80 via-cbc-dark-green/50 to-cbc-dark-green/90',
    },
    particlesId: {
        type: String,
        default: 'header-particles-js',
    },
});

const currentIndex = ref(0);
let backgroundTimer = null;

const normalizedImages = computed(() =>
    Array.isArray(props.images) && props.images.length ? props.images : []
);

onMounted(() => {
    if (normalizedImages.value.length > 1) {
        backgroundTimer = setInterval(() => {
            currentIndex.value = (currentIndex.value + 1) % normalizedImages.value.length;
        }, props.intervalMs);
    }
});

onBeforeUnmount(() => {
    if (backgroundTimer) {
        clearInterval(backgroundTimer);
    }
});
</script>

<template>
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 z-0" aria-hidden="true">
            <img
                v-for="(imagePath, index) in normalizedImages"
                :key="imagePath"
                :src="imagePath"
                :alt="`PIN carousel background ${index + 1}`"
                class="absolute inset-0 w-full h-full object-cover transition-opacity duration-1000"
                :class="index === currentIndex ? 'opacity-100' : 'opacity-0'"
                :loading="index === 0 ? 'eager' : 'lazy'"
            />
        </div>
        <div class="absolute inset-0 z-10" :class="overlayClass"></div>
        <div v-if="showParticles" class="absolute inset-0 z-20 pointer-events-none opacity-80">
            <particles-background :id="particlesId" />
        </div>
    </div>
</template>
