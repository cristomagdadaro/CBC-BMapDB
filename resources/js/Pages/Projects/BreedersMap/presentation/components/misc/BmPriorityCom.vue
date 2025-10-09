<script setup>
import {ref, reactive, computed, onMounted, onBeforeUnmount, watch, defineProps, defineEmits, defineExpose} from 'vue';
import { Link } from '@inertiajs/vue3';
import ArrowLeft from '@/Components/Icons/ArrowLeft.vue';
import ArrowRight from '@/Components/Icons/ArrowRight.vue';

const props = defineProps({
    items: {
        type: Array,
        default: () => [
            { name: 'Rice', image: '/img/commodities/p-rice.webp', data: { varieties: 21, research: 323, breeders: 32 }, route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rice&geo_location_filter=region&with=breeder,location,characteristics,additionalinfo' },
            { name: 'Corn', image: '/img/commodities/p-corn.webp', data: { varieties: 54, research: 565, breeders: 122 }, route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Corn&geo_location_filter=region' },
            { name: 'Cotton', image: '/img/commodities/p-cotton.webp', data: { varieties: 12, research: 123, breeders: 23 }, route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Cotton&geo_location_filter=region' },
            { name: 'Tomato', image: '/img/commodities/p-tomato.webp', data: { varieties: 32, research: 234, breeders: 45 }, route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Tomato&geo_location_filter=region' },
            { name: 'Eggplant', image: '/img/commodities/p-eggplant.webp', data: { varieties: 23, research: 234, breeders: 45 }, route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Eggplant&geo_location_filter=region' },
            { name: 'Rubber', image: '/img/commodities/p-rubber.webp', data: { varieties: 12, research: 123, breeders: 23 }, route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region' },
            { name: 'Adlay', image: '/img/commodities/p-adlay.webp', data: { varieties: 12, research: 123, breeders: 23 }, route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region' },
            { name: 'Mango', image: '/img/commodities/p-mango.webp', data: { varieties: 12, research: 123, breeders: 23 }, route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region' },
            { name: 'Quinoa', image: '/img/commodities/p-quinoa.webp', data: { varieties: 12, research: 123, breeders: 23 }, route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region' },
            { name: 'Abaca', image: '/img/commodities/p-abaca.webp', data: { varieties: 12, research: 123, breeders: 23 }, route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region' },
            { name: 'Banana', image: '/img/commodities/p-banana.webp', data: { varieties: 12, research: 123, breeders: 23 }, route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region' },
            { name: 'Coffee', image: '/img/commodities/p-coffee.webp', data: { varieties: 12, research: 123, breeders: 23 }, route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region' },
            { name: 'Coconut', image: '/img/commodities/p-coconut.webp', data: { varieties: 12, research: 123, breeders: 23 }, route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region' },
            { name: 'Sugarcane', image: '/img/commodities/p-sugarcane.webp', data: { varieties: 12, research: 123, breeders: 23 }, route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region' },
            { name: 'Cacao', image: '/img/commodities/p-cacao.webp', data: { varieties: 12, research: 123, breeders: 23 }, route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region' },
            { name: 'Pineapple', image: '/img/commodities/p-pineapple.webp', data: { varieties: 12, research: 123, breeders: 23 }, route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region' },
            { name: 'Cassava', image: '/img/commodities/p-cassava.webp', data: { varieties: 12, research: 123, breeders: 23 }, route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region' },
            { name: 'Sweet Potato', image: '/img/commodities/p-sweetpotato.webp', data: { varieties: 12, research: 123, breeders: 23 }, route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region' },
            { name: 'Peanut', image: '/img/commodities/p-peanut.webp', data: { varieties: 12, research: 123, breeders: 23 }, route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region' },
            { name: 'Papaya', image: '/img/commodities/p-papaya.webp', data: { varieties: 12, research: 123, breeders: 23 }, route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region' },
            { name: 'Durian', image: '/img/commodities/p-durian.webp', data: { varieties: 12, research: 123, breeders: 23 }, route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region' },
            { name: 'Jackfruit', image: '/img/commodities/p-jackfruit.webp', data: { varieties: 12, research: 123, breeders: 23 }, route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region' },
            { name: 'Soybean', image: '/img/commodities/p-soybean.webp', data: { varieties: 12, research: 123, breeders: 23 }, route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region' },
            { name: 'Mungbean', image: '/img/commodities/p-mungbean.webp', data: { varieties: 12, research: 123, breeders: 23 }, route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region' },
            { name: 'Taro', image: '/img/commodities/p-taro.webp', data: { varieties: 12, research: 123, breeders: 23 }, route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region' },
            { name: 'Yam', image: '/img/commodities/p-yam.webp', data: { varieties: 12, research: 123, breeders: 23 }, route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region' },
            { name: 'Black Pepper', image: '/img/commodities/p-blackpepper.webp', data: { varieties: 12, research: 123, breeders: 23 }, route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region' },
            { name: 'Ginger', image: '/img/commodities/p-ginger.webp', data: { varieties: 12, research: 123, breeders: 23 }, route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region' },
            { name: 'Onion', image: '/img/commodities/p-onion.webp', data: { varieties: 12, research: 123, breeders: 23 }, route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region' },
            { name: 'Garlic', image: '/img/commodities/p-garlic.webp', data: { varieties: 12, research: 123, breeders: 23 }, route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region' },
        ]
    },
    maxDisplay: { type: Number, default: 5 },
    centerScale: { type: Number, default: 1.30 },
    scaleFalloff: { type: Number, default: 0.35 },
    minScale: { type: Number, default: 0.45 },
    depthStep: { type: Number, default: 60 },
    maxDepth: { type: Number, default: 220 },
    xSpread: { type: Number, default: 14 },
    // New: spread for vertical orientation
    ySpread: { type: Number, default: 18 },
    // Breakpoint (px) under which carousel becomes vertical
    verticalBreakpoint: { type: Number, default: 640 },
    opacityFalloff: { type: Number, default: 0.5 },
    minOpacity: { type: Number, default: 0.15 },
    enableBlur: { type: Boolean, default: true },
    blurStep: { type: Number, default: 2 },
    maxBlur: { type: Number, default: 4 },
    swipeThreshold: { type: Number, default: 30 },
    multipleStepDistance: { type: Number, default: 80 },
    maxMomentumSteps: { type: Number, default: 4 },
    autoAdvance: { type: Boolean, default: true },
    autoAdvanceInterval: { type: Number, default: 1000 },
    interactionIdleMs: { type: Number, default: 3500 },
    placeholderImage: { type: String, default: 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==' },
    animationDurationMs: { type: Number, default: 900 },
    pulseDurationMs: { type: Number, default: 900 },
    emitInitial: { type: Boolean, default: true },
});

const emit = defineEmits(['change']);

// State
const containerRef = ref(null);
const headIndex = ref(0);
const direction = ref('forward'); // 'forward' | 'backward'
const currentName = ref(null);

// Orientation state
const isVertical = ref(false);
function updateOrientation() {
    if (typeof window === 'undefined') return;
    isVertical.value = window.innerWidth < props.verticalBreakpoint;
}

// Interaction state
const isPaused = ref(false);
const isInteracting = ref(false);
let interactionIdleTimer = null;
let autoAdvanceTimer = null;

// Touch state for momentum (axis-agnostic primary coordinate)
const touch = reactive({
    startPrimary: null,
    crossAxisStart: null,
    startTime: 0,
    positions: [], // {p,t}
});

// Lazy loading
const loaded = reactive(new Set());
const itemRefs = reactive({});
let observer = null;

// Computed values
const itemCount = computed(() => props.items.length);
const effectiveDisplay = computed(() => Math.min(props.maxDisplay, itemCount.value));
const centerIndex = computed(() => Math.floor(effectiveDisplay.value / 2));

const visibleItems = computed(() => {
    const arr = [];
    if (!itemCount.value) return arr;
    for (let i = 0; i < effectiveDisplay.value; i++) {
        const idx = (headIndex.value + i) % itemCount.value;
        arr.push(props.items[idx]);
    }
    return arr;
});

const currentItem = computed(() => props.items.find(i => i.name === currentName.value) || null);

// Watch center change -> emit
watch(visibleItems, () => updateCurrentCenter());
watch(() => headIndex.value, () => updateCurrentCenter());

function updateCurrentCenter() {
    const center = visibleItems.value[centerIndex.value];
    if (center && center.name !== currentName.value) {
        currentName.value = center.name;
        emit('change', currentItem.value);
    }
}

// Utility / formatting
function sanitizeId(name) {
    return 'commodity-' + String(name).toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
}

function markInteraction() {
    isInteracting.value = true;
    stopAutoAdvance();
    if (interactionIdleTimer) clearTimeout(interactionIdleTimer);
    interactionIdleTimer = setTimeout(() => {
        isInteracting.value = false;
        if (!isPaused.value && props.autoAdvance) startAutoAdvance();
    }, props.interactionIdleMs);
}

function pause(flag) {
    if (flag) {
        isPaused.value = true; stopAutoAdvance();
    } else {
        isPaused.value = false; if (!isInteracting.value && props.autoAdvance) startAutoAdvance();
    }
}

function startAutoAdvance() {
    if (!props.autoAdvance || autoAdvanceTimer || itemCount.value <= 1) return;
    autoAdvanceTimer = setInterval(() => {
        if (!isPaused.value && !isInteracting.value) nextItem();
    }, props.autoAdvanceInterval);
}

function stopAutoAdvance() {
    if (autoAdvanceTimer) { clearInterval(autoAdvanceTimer); autoAdvanceTimer = null; }
}

function advanceBy(steps) {
    if (!itemCount.value || steps === 0) return;
    const len = itemCount.value;
    const normalized = ((steps % len) + len) % len;
    if (normalized === 0) return;
    direction.value = steps > 0 ? 'forward' : 'backward';
    headIndex.value = (headIndex.value + normalized) % len;
}

function nextItem() { markInteraction(); advanceBy(1); }
function prevItem() { markInteraction(); advanceBy(-1); }

// Wheel support
function onWheel(e) {
    markInteraction();
    const delta = e.deltaY || e.detail || e.wheelDelta;
    if (delta < 0) nextItem(); else prevItem();
}

// Keyboard
function onKeyDown(e) {
    markInteraction();
    if (!isVertical.value) {
        if (e.key === 'ArrowRight') { e.preventDefault(); nextItem(); }
        else if (e.key === 'ArrowLeft') { e.preventDefault(); prevItem(); }
    } else {
        if (e.key === 'ArrowDown') { e.preventDefault(); nextItem(); }
        else if (e.key === 'ArrowUp') { e.preventDefault(); prevItem(); }
    }
    if (e.key === 'Home') { e.preventDefault(); headIndex.value = 0; }
    else if (e.key === 'End') { e.preventDefault(); headIndex.value = (itemCount.value - effectiveDisplay.value + itemCount.value) % itemCount.value; }
}

// Helpers for touch axis
function primaryCoord(t) { return isVertical.value ? t.clientY : t.clientX; }
function crossCoord(t) { return isVertical.value ? t.clientX : t.clientY; }

// Touch with inertia (axis aware)
function onTouchStart(e) {
    if (!e.touches?.length) return;
    markInteraction();
    const t = e.touches[0];
    touch.startPrimary = primaryCoord(t);
    touch.crossAxisStart = crossCoord(t);
    touch.startTime = performance.now();
    touch.positions = [{ p: primaryCoord(t), t: touch.startTime }];
}
function onTouchMove(e) {
    if (!e.touches?.length || touch.startPrimary === null) return;
    const t = e.touches[0];
    const dPrimary = primaryCoord(t) - touch.startPrimary;
    const dCross = crossCoord(t) - touch.crossAxisStart;
    const now = performance.now();
    touch.positions.push({ p: primaryCoord(t), t: now });
    if (touch.positions.length > 6) touch.positions.shift();
    // Prevent scrolling if gesture is along primary axis predominantly
    if (Math.abs(dPrimary) > Math.abs(dCross)) e.preventDefault();
}
function onTouchEnd(e) {
    if (touch.startPrimary === null) return;
    const t = e.changedTouches?.[0];
    if (!t) return;
    const dPrimary = primaryCoord(t) - touch.startPrimary;
    const totalTime = performance.now() - touch.startTime;
    // Velocity
    let velocity;
    if (touch.positions.length >= 2) {
        const first = touch.positions[0];
        const last = touch.positions[touch.positions.length - 1];
        velocity = (last.p - first.p) / (last.t - first.t || 1); // px per ms
    } else {
        velocity = dPrimary / (totalTime || 1);
    }
    const absD = Math.abs(dPrimary);
    let steps = 0;
    if (absD > props.swipeThreshold) {
        const distanceSteps = Math.floor((absD - props.swipeThreshold) / props.multipleStepDistance) + 1;
        const absVel = Math.abs(velocity);
        let velBonus = 0;
        if (absVel > 1.4) velBonus = 2; else if (absVel > 0.9) velBonus = 1;
        steps = Math.min(props.maxMomentumSteps, distanceSteps + velBonus);
    }
    if (steps > 0) {
        const sign = dPrimary < 0 ? 1 : -1;
        advanceBy(sign * steps);
    } else if (absD > props.swipeThreshold) {
        advanceBy(dPrimary < 0 ? 1 : -1);
    }
    touch.startPrimary = touch.crossAxisStart = null;
    touch.positions = [];
}

// Lazy loading (IntersectionObserver)
function setItemRef(name, el) {
    if (!el) return;
    itemRefs[name] = el;
    if (observer) observer.observe(el);
}
function initObserver() {
    if (typeof window === 'undefined' || !('IntersectionObserver' in window)) return;
    observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const id = entry.target.getAttribute('data-name');
                if (id) loaded.add(id);
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    Object.keys(itemRefs).forEach(k => observer.observe(itemRefs[k]));
}
function getImageSrc(item) {
    return loaded.has(item.name) ? item.image : props.placeholderImage;
}

// Style computation for each visible card (orientation aware)
function getItemStyle(idx /* item not needed */) {
    const offset = idx - centerIndex.value; // 0 is center
    const abs = Math.abs(offset);
    // Make center larger and sides smaller / dimmer
    const centerBoost = 1.05; // extra scale boost for the active center
    const sideDiminish = 0.92; // slight diminish for non-center before falloff
    const baseScale = offset === 0
        ? props.centerScale * centerBoost
        : (props.centerScale * sideDiminish) - abs * props.scaleFalloff;
    const scale = Math.max(props.minScale, baseScale);
    const translateZ = -Math.min(abs * props.depthStep, props.maxDepth);
    const spread = isVertical.value ? props.ySpread : props.xSpread;
    const translatePrimary = offset * -spread; // negative for forward to create depth illusion
    let opacity = offset === 0 ? 1 : Math.max(props.minOpacity, 1 - (abs * (props.opacityFalloff + 0.15))); // stronger fade
    const brightness = offset === 0 ? 1.05 : Math.max(0.2, 0.55 - (abs - 1) * 0.18);
    const saturation = offset === 0 ? 1.2 : 0.5;
    const blur = props.enableBlur && abs > 0 ? Math.min(abs * props.blurStep, props.maxBlur) : 0;

    const transform = isVertical.value
        ? `translate3d(0, ${translatePrimary}%, ${translateZ}px) scale(${scale.toFixed(4)})`
        : `translate3d(${translatePrimary}%,0,${translateZ}px) scale(${scale.toFixed(4)})`;

    return {
        '--offset': offset,
        'z-index': 400 - abs * 12,
        transform,
        opacity,
        filter: `brightness(${brightness}) saturate(${saturation}) blur(${blur}px)`
    };
}

function goTo(name) {
    const idx = props.items.findIndex(i => i.name === name);
    if (idx === -1) return;
    headIndex.value = (idx - centerIndex.value + itemCount.value) % itemCount.value;
    updateCurrentCenter();
}

// Expose imperative API
function play() { pause(false); }
function prev() { prevItem(); }
function next() { nextItem(); }
defineExpose({ play, pause, prev, next, goTo });

onMounted(() => {
    updateOrientation();
    if (typeof window !== 'undefined') window.addEventListener('resize', updateOrientation, { passive: true });
    updateCurrentCenter();
    if (containerRef.value) containerRef.value.style.overflow = 'hidden';
    initObserver();
    if (props.autoAdvance) startAutoAdvance();
    if (props.emitInitial && currentItem.value) {
        emit('change', currentItem.value);
    }
});
onBeforeUnmount(() => {
    stopAutoAdvance();
    if (interactionIdleTimer) clearTimeout(interactionIdleTimer);
    if (observer) { try { observer.disconnect(); } catch (_) {} }
    if (typeof window !== 'undefined') window.removeEventListener('resize', updateOrientation);
});
</script>

<template>
    <div class="overflow-x-hidden overflow-y-hidden py-5">
        <h3 class="text-center text-subtitle">Priority Commodities</h3>
        <p class="text-normal text-center text-dark-color">Currently, the center has identified {{ itemCount }} priority commodities</p>
        <div
            ref="containerRef"
            :class="['relative select-none justify-center my-2 w-full perspective-1000 focus:outline-none drop-shadow', isVertical ? 'flex flex-col min-h-[26rem]' : 'flex flex-row min-h-[10rem] sm:min-h-[20rem]']"
            role="listbox"
            :aria-activedescendant="currentName ? sanitizeId(currentName) : null"
            tabindex="0"
            aria-label="Priority commodities carousel"
            :aria-orientation="isVertical ? 'vertical' : 'horizontal'"
            :data-orientation="isVertical ? 'vertical' : 'horizontal'"
            @wheel.prevent="onWheel"
            @touchstart.passive="onTouchStart"
            @touchmove="onTouchMove"
            @touchend="onTouchEnd"
            @keydown="onKeyDown"
        >
            <!-- Mobile arrows (hide in vertical orientation to encourage swipe) -->
            <div v-if="!isVertical" class="flex sm:hidden text-gray-100 absolute z-30 h-full justify-between w-full pointer-events-none" aria-label="Carousel navigation controls">
                <ArrowLeft class="w-16 h-auto pointer-events-auto" @click="prevItem" />
                <ArrowRight class="w-16 h-auto pointer-events-auto" @click="nextItem" />
            </div>

            <!-- Items container orientation aware -->
            <div :class="['flex gap-0 items-stretch will-change-transform relative', isVertical ? 'flex-col justify-center' : 'flex-row']">
                <div
                    v-for="(item, idx) in visibleItems" :key="item.name"
                    class="carousel-card relative bg-cbc-dark-green sm:min-w-[16vw] min-w-[9rem] text-gray-100"
                    :class="{ 'min-h-[6rem] sm:min-h-[8rem]': isVertical, 'ring-2 ring-cbc-dark-green/60': item.name === currentName }"
                    :id="sanitizeId(item.name)"
                    role="option"
                    :aria-selected="item.name === currentName"
                    :aria-hidden="item.name === currentName ? 'false' : 'true'"
                    :data-name="item.name"
                    :style="getItemStyle(idx)"
                    :ref="el => setItemRef(item.name, el)"
                >
                    <template  v-if="item.name === currentName" >
                        <Link :href="item.route" class="absolute inset-0">
                            <img :src="getImageSrc(item)" loading="lazy" :alt="item.name" class="absolute inset-0 w-full h-full object-cover md:center object-bottom rounded transition-opacity duration-500 ease-out" :class="{'opacity-0': !loaded.has(item.name), 'opacity-100': loaded.has(item.name)}" />
                            <div class="relative z-10 flex items-center justify-center h-full font-bold pointer-events-none drop-shadow-xl text-2xl sm:text-3xl">
                                {{ item.name }}
                                <span class="text-white absolute bottom-3 right-3 text-[.60rem] sm:text-xs opacity-40 tracking-wide">
                                    Gemini
                                </span>
                            </div>
                        </Link>
                    </template>
                    <template v-else>
                        <div @click="advanceBy(1)" class="absolute inset-0">
                            <img :src="getImageSrc(item)" loading="lazy" :alt="item.name" class="absolute inset-0 w-full h-full object-cover md:center object-bottom rounded transition-opacity duration-500 ease-out" :class="{'opacity-0': !loaded.has(item.name), 'opacity-100': loaded.has(item.name)}" />
                            <div class="relative z-10 flex items-center justify-center h-full font-bold pointer-events-none drop-shadow-xl text-base sm:text-xl opacity-70">
                                {{ item.name }}
                            </div>
                        </div>
                    </template>

                </div>
            </div>
        </div>
        <p class="text-xs text-gray-500 mt-2">Gemini Prompt: generate a close up image of a [commodity]. professional photography style with depth of field view effect, do not apply vignetting. Using rule of third place the Subject at the bottom left. Add multiple copies of the subject to make it look like abundant. Show the main subject whole.</p>
        <div v-if="currentItem" class="flex hidden flex-col justify-center text-gray-900 text-normal" aria-live="polite">
            <div class="flex gap-2 justify-center"><span class="font-bold">Breeders</span><span>{{ currentItem.data.breeders }}</span></div>
            <div class="flex gap-2 justify-center"><span class="font-bold">Varieties</span><span>{{ currentItem.data.varieties }}</span></div>
            <div class="flex gap-2 justify-center"><span class="font-bold">Publications</span><span>{{ currentItem.data.research }}</span></div>
        </div>
    </div>
</template>

<style scoped>
/* Core styling retained & enhanced */
.perspective-1000 { perspective: 1300px; }
.carousel-card { --anim-dur: 560ms; position: relative; overflow: hidden; border-radius: .75rem; box-shadow: 0 4px 14px -2px rgba(0,0,0,.45); will-change: transform, opacity, filter; }
.carousel-card[aria-selected="true"] { box-shadow: 0 12px 38px -6px rgba(0,0,0,.65); }
.carousel-card::after { content: ''; position: absolute; inset:0; background: radial-gradient(circle at 50% 55%, rgba(255,255,255,0.15), rgba(0,0,0,.55)); opacity: 0; transition: opacity 400ms ease; pointer-events:none; mix-blend-mode: overlay; }
.carousel-card[aria-selected="true"]::after { opacity: .65; }

.carousel-card { transition: transform var(--anim-dur) cubic-bezier(.25,.9,.34,1.31),
opacity var(--anim-dur) ease-out,
filter var(--anim-dur) ease-out,
box-shadow var(--anim-dur) ease-out; }

[data-orientation="vertical"] .carousel-card { width: 100%; }

@media (prefers-reduced-motion: reduce) {
    .carousel-card { transition: none !important; animation: none !important; }
}
</style>
