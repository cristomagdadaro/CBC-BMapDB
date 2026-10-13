<script setup>
import {computed} from 'vue'

const props = defineProps({
    items: {type: Array, default: () => []},
    x: {type: Number, required: true},
    y: {type: Number, required: true},
    radius: {type: Number, default: 80},
    locationName: { type: String, default: '' },
    dataType: {type: String, default: 'commodities'},
    loading: {type: Boolean, default: false},
    visible: {type: Boolean, default: true},
})

const emit = defineEmits(['close', 'iconClick', 'enter', 'select'])

const positions = computed(() => {
    const n = props.items.length || 1
    const angleStep = (2 * Math.PI) / n
    return props.items.map((_, i) => {
        const angle = i * angleStep - (Math.PI / 2) // Start from top
        const x = props.radius * Math.cos(angle)
        const y = props.radius * Math.sin(angle)
        return {
            transform: `translate(${x}px, ${y}px)`
        }
    })
})

const DEFAULT_FALLBACK_IMAGE = '/img/logo_cbc.webp'

const getImageSrc = (image) => {
    if (!image || typeof image !== 'string') {
        return DEFAULT_FALLBACK_IMAGE
    }

    if (image.startsWith('data:image') || image.startsWith('http')) {
        return image
    }

    return image.startsWith('/') ? image : `/${image}`
}

const onImageError = (e) => {
    if (e?.target && e.target.src !== DEFAULT_FALLBACK_IMAGE) {
        e.target.src = DEFAULT_FALLBACK_IMAGE
    }
}

const handleItemClick = (e, item) => {
    e.preventDefault()
    emit('select', { ...item, data_type: props.dataType })
}
</script>

<template>
    <transition name="orbit-container">
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

                <!-- Location Name -->
                <div v-if="locationName" class="absolute top-full mt-2 w-max max-w-xs bg-gray-800 text-white text-xs rounded py-1 px-2 pointer-events-none">
                    {{ locationName }}
                </div>

                <!-- orbiting icons container -->
                <transition-group
                    tag="div"
                    name="orbit-item"
                    class="absolute inset-0"
                    style="pointer-events: none;"
                >
                    <button
                        v-for="(item, i) in items"
                        :key="item.id"
                        type="button"
                        class="orbit-item absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 block w-10 h-10 rounded-full ring-2 ring-white shadow pointer-events-auto bg-white overflow-hidden hover:ring-emerald-400 hover:scale-110 transition-transform focus:outline-none focus:ring-2 focus:ring-emerald-500"
                        :style="[positions[i], { transitionDelay: `${i * 50}ms` }]"
                        :title="item.label || item.name || 'View basic info'"
                        @click="handleItemClick($event, item)"
                    >
                        <img :src="getImageSrc(item.image)" :alt="item.label || 'icon'" @error="onImageError" class="w-full h-full object-cover"/>
                    </button>
                </transition-group>
            </div>
        </div>
    </transition>
</template>

<style scoped>
/* Orbit Container Transition */
.orbit-container-enter-active,
.orbit-container-leave-active {
    transition: opacity 300ms ease, transform 300ms ease;
}
.orbit-container-enter-from,
.orbit-container-leave-to {
    opacity: 0;
    transform: translate(-50%, -50%) scale(0.8);
}

/* Orbit Item Transition */
.orbit-item {
    transition: opacity 300ms ease, transform 300ms ease;
}
.orbit-item-enter-from,
.orbit-item-leave-to {
    opacity: 0;
    transform: translate(0, 0) scale(0.5);
}
.orbit-item-leave-active {
    position: absolute; /* Required for <transition-group> leave animations */
}
</style>
