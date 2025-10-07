<script setup>
import {computed} from 'vue'
import { MotionDirective as vMotion } from '@vueuse/motion'

const props = defineProps({
    items: {type: Array, default: () => []},
    x: {type: Number, required: true},
    y: {type: Number, required: true},
    radius: {type: Number, default: 80},
    dataType: {type: String, default: 'commodities'},
    loading: {type: Boolean, default: false},
    visible: {type: Boolean, default: true},
})

const emit = defineEmits(['close', 'iconClick', 'enter'])

const positions = computed(() => {
    const n = props.items.length || 1
    const step = (2 * Math.PI) / n
    return props.items.map((_, i) => {
        const angle = i * step
        // translate radius from center using CSS transform chain
        return {
            transform: `rotate(${(angle * 180) / Math.PI}deg) translate(${props.radius}px) rotate(${(-angle * 180) / Math.PI}deg)`
        }
    })
})

const toHref = (item) => {
    if (props.dataType === 'breeders') return `/projects/breedersmap/breeders/${item.id}`
    return `/projects/breedersmap/commodity/${item.id}`
}

const getImageSrc = (image) => {
    if (typeof image !== 'string') {
        // Return a placeholder for invalid image data
        return 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7'; // 1x1 transparent pixel
    }

    // If it's a data URI or a full URL, use it as is.
    if (image.startsWith('data:image') || image.startsWith('http')) {
        return image;
    }

    // If it's a relative path, prepend the base URL.
}
</script>

<template>
    <div
        v-if="visible"
        class="absolute pointer-events-auto"
        :style="{ left: x + 'px', top: y + 'px', transform: 'translate(-50%, -50%)', zIndex: 1001 }"
        @mouseleave="() => emit('close')"
        @mouseenter="() => emit('enter')"
    >
        <!-- central hub -->
        <div
            class="relative w-10 h-10 rounded-full bg-white shadow ring-2 ring-blue-500 flex items-center justify-center">
            <div v-if="loading"
                 class="w-5 h-5 border-2 border-blue-500 border-t-transparent rounded-full animate-spin"/>
            <div v-else class="w-2 h-2 rounded-full bg-blue-500"/>

            <!-- orbiting icons container -->
            <div class="absolute inset-0" style="pointer-events: none;">
                <template v-for="(item, i) in items" :key="item.id">
                    <a
                        v-motion
                        :initial="{ opacity: 0, scale: 0.6 }"
                        :enter="{ opacity: 1, scale: 1, transition: { delay: i * 0.06 } }"
                        :leave="{ opacity: 0, scale: 0.6, transition: { duration: 0.12 } }"
                        class="absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 block w-10 h-10 rounded-full ring-2 ring-white shadow pointer-events-auto bg-white overflow-hidden hover:ring-blue-400"
                        :style="positions[i]"
                        :href="toHref(item)"
                    >
                        <img :src="getImageSrc(item.image)" :alt="item.label || 'icon'" class="w-full h-full object-cover"/>
                    </a>
                </template>
            </div>
        </div>
    </div>
</template>

<style scoped>
</style>
