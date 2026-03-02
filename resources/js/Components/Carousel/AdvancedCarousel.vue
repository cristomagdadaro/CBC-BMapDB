<script setup>
import {ref, reactive, computed, onMounted, onBeforeUnmount, watch, defineProps, defineEmits, defineExpose} from 'vue';
import {Link} from '@inertiajs/vue3';

/*
Reusable AdvancedCarousel
Features:
- Horizontal by default, auto-switch to vertical under a breakpoint
- Momentum / inertial swipe (touch)
- Mouse wheel, keyboard navigation
- Circular data pointer (no array mutation)
- Auto-advance with pause-on-hover / pause-on-interaction
- Lazy image loading (IntersectionObserver) + placeholder
- Scale / depth / blur / opacity layering with center emphasis
- Emits 'change' when center item changes
- Slots for custom arrow buttons & item rendering

Slots:
  #item="{ item, isCenter, index, offset, centerIndex }" : custom card content
  #arrow-left : left arrow button (fallback provided)
  #arrow-right : right arrow button (fallback provided)

Props largely mirror the original specialized component to ease migration.
*/

const props = defineProps({
    items: {type: Array, default: () => []},
    // Data field keys
    nameProp: {type: String, default: 'name'},
    imageProp: {type: String, default: 'image'},
    routeProp: {type: String, default: 'route'},
    // Display sizing & depth
    maxDisplay: {type: Number, default: 5},
    centerScale: {type: Number, default: 1.30},
    scaleFalloff: {type: Number, default: 0.35},
    minScale: {type: Number, default: 0.45},
    depthStep: {type: Number, default: 60},
    maxDepth: {type: Number, default: 220},
    xSpread: {type: Number, default: 14},
    ySpread: {type: Number, default: 18},
    verticalBreakpoint: {type: Number, default: 640},
    opacityFalloff: {type: Number, default: 0.5},
    minOpacity: {type: Number, default: 0.15},
    enableBlur: {type: Boolean, default: true},
    blurStep: {type: Number, default: 2},
    maxBlur: {type: Number, default: 4},
    // Interaction / motion
    swipeThreshold: {type: Number, default: 30},
    multipleStepDistance: {type: Number, default: 80},
    maxMomentumSteps: {type: Number, default: 4},
    autoAdvance: {type: Boolean, default: true},
    autoAdvanceInterval: {type: Number, default: 2500},
    interactionIdleMs: {type: Number, default: 3500},
    // Behavior
    emitInitial: {type: Boolean, default: true},
    observeImages: {type: Boolean, default: true},
    placeholderImage: {type: String, default: 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw=='},
    showArrows: {type: Boolean, default: true},
    arrowOnHoverOnly: {type: Boolean, default: false},
    // Accessibility / semantics
    ariaLabel: {type: String, default: 'Carousel'},
    // Styling hooks
    centerExtraScale: {type: Number, default: 1.05},
    sideDiminish: {type: Number, default: 0.92},
    // New: responsive sizing
    responsive: {type: Boolean, default: true},
    aspectRatio: {type: [String, Number, null], default: '16/9'},
    // Card base sizes (used as fallbacks / clamp bounds)
    cardMinWidth: {type: String, default: '12rem'},
    cardFluidWidth: {type: String, default: '30vw'},
    cardWidth: {type: String, default: '22rem'},
    cardHeight: {type: String, default: '14rem'},
});

const emit = defineEmits(['change']);

// Core state
const headIndex = ref(0);
const currentKey = ref(null);
const isVertical = ref(false);
const containerRef = ref(null);

// Interaction flags
const isPaused = ref(false);
const isInteracting = ref(false);
let interactionIdleTimer = null;
let autoAdvanceTimer = null;

// Touch state
const touch = reactive({startPrimary: null, crossAxisStart: null, startTime: 0, positions: []});

// Lazy loading state
const loaded = reactive(new Set());
const itemRefs = reactive({});
let observer = null;
// Track actual image load completion (not just intersection)
const imageReady = reactive(new Set());

/*
Reusable AdvancedCarousel
Features:
- Horizontal by default, auto-switch to vertical under a breakpoint
- Momentum / inertial swipe (touch)
- Mouse wheel, keyboard navigation
- Circular data pointer (no array mutation)
- Auto-advance with pause-on-hover / pause-on-interaction
- Lazy image loading (IntersectionObserver) + placeholder
- Scale / depth / blur / opacity layering with center emphasis
- Emits 'change' when center item changes
- Slots for custom arrow buttons & item rendering

Slots:
  #item="{ item, isCenter, index, offset, centerIndex }" : custom card content
  #arrow-left : left arrow button (fallback provided)
  #arrow-right : right arrow button (fallback provided)

Props largely mirror the original specialized component to ease migration.
*/

// Derived values
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

// Overall busy state (for ARIA) if nothing to show yet or some visible images not ready
const anyLoading = computed(() => {
    if (itemCount.value === 0) return true;
    if (!props.observeImages) return false;
    return visibleItems.value.some(i => !isItemReady(i));
});

const currentItem = computed(() => props.items.find(i => getItemName(i) === currentKey.value) || null);

watch([visibleItems, () => headIndex.value], () => updateCurrentCenter());

function updateCurrentCenter() {
    const center = visibleItems.value[centerIndex.value];
    if (!center) return;
    const key = getItemName(center);
    if (key !== currentKey.value) {
        currentKey.value = key;
        emit('change', currentItem.value);
    }
}

function getItemName(item) {
    return String(item?.[props.nameProp] ?? '');
}

function sanitizeId(val) {
    const name = typeof val === 'string' ? val : getItemName(val);
    return 'carousel-item-' + String(name).toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
}

function updateOrientation() {
    if (typeof window === 'undefined') return;
    isVertical.value = window.innerWidth < props.verticalBreakpoint;
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
        isPaused.value = true;
        stopAutoAdvance();
    } else {
        isPaused.value = false;
        if (!isInteracting.value && props.autoAdvance) startAutoAdvance();
    }
}

function startAutoAdvance() {
    if (!props.autoAdvance || autoAdvanceTimer || itemCount.value <= 1) return;
    autoAdvanceTimer = setInterval(() => {
        if (!isPaused.value && !isInteracting.value) next();
    }, props.autoAdvanceInterval);
}

function stopAutoAdvance() {
    if (autoAdvanceTimer) {
        clearInterval(autoAdvanceTimer);
        autoAdvanceTimer = null;
    }
}

function advanceBy(steps) {
    if (!itemCount.value || steps === 0) return;
    const len = itemCount.value;
    const normalized = ((steps % len) + len) % len;
    if (normalized === 0) return;
    headIndex.value = (headIndex.value + normalized) % len;
}

function next() {
    markInteraction();
    advanceBy(1);
}

function prev() {
    markInteraction();
    advanceBy(-1);
}

function onWheel(e) {
    markInteraction();
    const delta = e.deltaY || e.detail || e.wheelDelta;
    if (delta < 0) next(); else prev();
}

function onKeyDown(e) {
    markInteraction();
    if (!isVertical.value) {
        if (e.key === 'ArrowRight') {
            e.preventDefault();
            next();
        } else if (e.key === 'ArrowLeft') {
            e.preventDefault();
            prev();
        }
    } else {
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            next();
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            prev();
        }
    }
    if (e.key === 'Home') {
        e.preventDefault();
        headIndex.value = 0;
    } else if (e.key === 'End') {
        e.preventDefault();
        headIndex.value = (itemCount.value - effectiveDisplay.value + itemCount.value) % itemCount.value;
    }
}

// Interaction / motion helpers
function primaryCoord(t) {
    return t.clientX;
}

function crossCoord(t) {
    return t.clientY;
}

function onTouchStart(e) {
    if (!e.touches?.length) return;
    markInteraction();
    const t = e.touches[0];
    touch.startPrimary = primaryCoord(t);
    touch.crossAxisStart = crossCoord(t);
    touch.startTime = performance.now();
    touch.positions = [{p: primaryCoord(t), t: touch.startTime}];
}

function onTouchMove(e) {
    if (!e.touches?.length || touch.startPrimary === null) return;
    const t = e.touches[0];
    const dPrimary = primaryCoord(t) - touch.startPrimary;
    const dCross = crossCoord(t) - touch.crossAxisStart;
    const now = performance.now();
    touch.positions.push({p: primaryCoord(t), t: now});
    if (touch.positions.length > 6) touch.positions.shift();
    if (Math.abs(dPrimary) > Math.abs(dCross)) e.preventDefault();
}

function onTouchEnd(e) {
    if (touch.startPrimary === null) return;
    const t = e.changedTouches?.[0];
    if (!t) return;
    const dPrimary = primaryCoord(t) - touch.startPrimary;
    const totalTime = performance.now() - touch.startTime;
    let velocity;
    if (touch.positions.length >= 2) {
        const first = touch.positions[0];
        const last = touch.positions[touch.positions.length - 1];
        velocity = (last.p - first.p) / (last.t - first.t || 1);
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

function setItemRef(key, el) {
    if (!el) return;
    itemRefs[key] = el;
    if (observer) observer.observe(el);
}

function initObserver() {
    if (!props.observeImages) return;
    if (typeof window === 'undefined' || !('IntersectionObserver' in window)) return;
    observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const id = entry.target.getAttribute('data-key');
                if (id) loaded.add(id);
                observer.unobserve(entry.target);
            }
        });
    }, {threshold: 0.1});
    Object.keys(itemRefs).forEach(k => observer.observe(itemRefs[k]));
}

// Return the src to use; placeholder until intersected, then real image
function getImageSrc(item) {
    const key = getItemName(item);
    return loaded.has(key) || !props.observeImages ? (item?.[props.imageProp] || props.placeholderImage) : props.placeholderImage;
}

// Image load handlers to mark resource readiness
function onImgLoad(key, evt) {
    // Ignore placeholder loads; only mark ready when the real image is loaded
    const src = evt?.target?.getAttribute('src') || '';
    if (!src || src === props.placeholderImage) return;
    imageReady.add(key);
}

function onImgError(key) {
    // Prevent perpetual spinner; mark as ready even on error
    imageReady.add(key);
}

// Whether a given item’s media is ready (intersection + real image loaded)
function isItemReady(item) {
    if (!props.observeImages) return true;
    const key = getItemName(item);
    if (!item?.[props.imageProp]) return true; // nothing to load
    return loaded.has(key) && imageReady.has(key);
}

function getItemStyle(idx) {
    const offset = idx - centerIndex.value;
    const abs = Math.abs(offset);
    const baseScale = offset === 0
        ? props.centerScale * props.centerExtraScale
        : (props.centerScale * props.sideDiminish) - abs * props.scaleFalloff;
    const scale = Math.max(props.minScale, baseScale);
    const translateZ = -Math.min(abs * props.depthStep, props.maxDepth);
    const spread = isVertical.value ? props.ySpread : props.xSpread;
    const translatePrimary = offset * -spread;
    let opacity = offset === 0 ? 1 : Math.max(props.minOpacity, 1 - (abs * (props.opacityFalloff + 0.15)));
    const brightness = offset === 0 ? 1.05 : Math.max(0.2, 0.55 - (abs - 1) * 0.18);
    const saturation = offset === 0 ? 1.2 : 0.5;
    const blur = props.enableBlur && abs > 0 ? Math.min(abs * props.blurStep, props.maxBlur) : 0;
    const transform = isVertical.value
        ? `translate3d(0, ${translatePrimary}%, ${translateZ}px) scale(${scale.toFixed(4)})`
        : `translate3d(${translatePrimary}%,0,${translateZ}px) scale(${scale.toFixed(4)})`;

  // Responsive sizing logic
  const widthValue = isVertical.value
    ? '100%'
    : (props.responsive ? `clamp(${props.cardMinWidth}, ${props.cardFluidWidth}, ${props.cardWidth})` : props.cardWidth);

  const style = {
    '--offset': offset,
    'z-index': 400 - abs * 12,
    transform,
    opacity,
    filter: `brightness(${brightness}) saturate(${saturation}) blur(${blur}px)`,
    width: widthValue,
  };

  if (props.responsive && props.aspectRatio) {
    style.aspectRatio = String(props.aspectRatio);
  } else {
    style.height = props.cardHeight;
  }
  return style;
}

function goTo(key) {
    const idx = props.items.findIndex(i => getItemName(i) === key);
    if (idx === -1) return;
    headIndex.value = (idx - centerIndex.value + itemCount.value) % itemCount.value;
    updateCurrentCenter();
}

function play() {
    pause(false);
}

function stop() {
    pause(true);
}

function onMouseEnter() {
    pause(true);
}

function onMouseLeave() {
    pause(false);
}

function onFocusIn() {
    pause(true);
}

function onFocusOut() {
    pause(false);
}

onMounted(() => {
    updateOrientation();
    if (typeof window !== 'undefined') window.addEventListener('resize', updateOrientation, {passive: true});
    updateCurrentCenter();
    initObserver();
    if (props.autoAdvance) startAutoAdvance();
    if (props.emitInitial && currentItem.value) emit('change', currentItem.value);
});
onBeforeUnmount(() => {
    stopAutoAdvance();
    if (interactionIdleTimer) clearTimeout(interactionIdleTimer);
    if (observer) {
        try {
            observer.disconnect();
        } catch (_) {
        }
    }
    if (typeof window !== 'undefined') window.removeEventListener('resize', updateOrientation);
});

defineExpose({next, prev, goTo, play, stop, pause, getCurrent: () => currentItem.value});
</script>

<template>
    <div
        ref="containerRef"
        class="advanced-carousel relative  flex justify-center w-fit mx-auto sm:w-full perspective-1000 focus:outline-none overflow-hidden"
        role="listbox"
        :aria-activedescendant="currentKey ? sanitizeId(currentKey) : null"
        tabindex="0"
        :aria-label="ariaLabel"
        :aria-orientation="isVertical ? 'vertical' : 'horizontal'"
        :data-orientation="isVertical ? 'vertical' : 'horizontal'"
        :aria-busy="anyLoading"
        @wheel.prevent="onWheel"
        @touchstart.passive="onTouchStart"
        @touchmove="onTouchMove"
        @touchend="onTouchEnd"
        @keydown="onKeyDown"
        @mouseenter="onMouseEnter" @mouseleave="onMouseLeave"
        @focusin="onFocusIn" @focusout="onFocusOut"
    >
        <!-- Arrows -->
        <div v-if="showArrows" class="absolute inset-0 pointer-events-none z-[999]"
             :class="{ 'opacity-0 hover:opacity-100 transition-opacity': arrowOnHoverOnly }">
            <div v-if="!isVertical" class="flex h-full justify-between items-center px-1 sm:px-2">
                <div class="pointer-events-auto">
                    <slot name="arrow-left">
                        <button type="button"
                                class="p-2 rounded-full bg-black/40 hover:bg-black/60 text-white focus:outline-none"
                                aria-label="Previous" @click="prev">‹
                        </button>
                    </slot>
                </div>
                <div class="pointer-events-auto">
                    <slot name="arrow-right">
                        <button type="button"
                                class="p-2 rounded-full bg-black/40 hover:bg-black/60 text-white focus:outline-none"
                                aria-label="Next" @click="next">›
                        </button>
                    </slot>
                </div>
            </div>
        </div>

        <!-- Items or global loading -->
        <template v-if="itemCount > 0">
            <div
                :class="['flex gap-4 sm:gap-6 px-2 sm:px-4 py-4 items-stretch will-change-transform relative overflow-hidden', isVertical ? 'flex-col justify-center' : 'flex-row']">
                <div
                    v-for="(item, idx) in visibleItems"
                    :key="getItemName(item)"
                    class="ac-card relative bg-neutral-800/70 text-white rounded-2xl ring-1 ring-white/10 shadow-2xl"
                    :class="{ 'ring-2 ring-white/30': getItemName(item) === currentKey }"
                    :id="sanitizeId(item)"
                    role="option"
                    :data-key="getItemName(item)"
                    :aria-selected="getItemName(item) === currentKey"
                    :aria-hidden="getItemName(item) === currentKey ? 'false' : 'true'"
                    :style="getItemStyle(idx)"
                    :ref="el => setItemRef(getItemName(item), el)"
                >
                    <div v-if="!isItemReady(item)"
                         class="absolute inset-0 z-30 flex items-center justify-center pointer-events-none">
                        <slot name="loading">
                            <div class="ac-spinner" aria-hidden="true"></div>
                        </slot>
                    </div>
                    <div class="absolute inset-0">
                        <img
                            v-if="item[imageProp]"
                            :src="getImageSrc(item)"
                            :alt="getItemName(item)"
                            loading="lazy"
                            class="absolute inset-0 w-full h-full object-cover rounded-[inherit] transition-opacity duration-500 ease-out"
                            :class="{'opacity-0': observeImages && !loaded.has(getItemName(item)), 'opacity-100': !observeImages || loaded.has(getItemName(item))}"
                            @load="onImgLoad(getItemName(item), $event)"
                            @error="onImgError(getItemName(item))"
                        />
                        <div
                            class="relative z-10 flex items-center justify-center h-full font-semibold pointer-events-none  drop-shadow-md p-2">
                            <slot name="item" :item="item" :isCenter="idx === centerIndex" :index="idx"
                                  :offset="idx - centerIndex" :centerIndex="centerIndex">
                                <span class="text-sm sm:text-lg"
                                      :class="getItemName(item) === currentKey ? 'scale-110 font-bold' : 'opacity-70'">{{
                                        getItemName(item)
                                    }}</span>
                            </slot>
                        </div>
                    </div>

                    <!-- Interaction layer: only center is a link; sides act as prev/next -->
                    <template v-if="idx === centerIndex">
                        <component
                            v-if="item[routeProp]"
                            :is="Link"
                            :href="item[routeProp]"
                            class="absolute inset-0 z-40 block"
                            :aria-label="`Open ${getItemName(item)}`"
                        />
                    </template>
                    <template v-else>
                        <button
                            type="button"
                            class="absolute inset-0 z-40 block bg-transparent"
                            :aria-label="(idx < centerIndex) ? 'Previous' : 'Next'"
                            @click="idx < centerIndex ? prev() : next()"
                        />
                    </template>
                </div>
            </div>
        </template>
        <template v-else>
            <div class="absolute inset-0 flex items-center justify-center">
                <slot name="loading">
                    <div class="ac-spinner" role="status" aria-label="Loading"></div>
                </slot>
            </div>
        </template>
    </div>
</template>

<style scoped>
.perspective-1000 {
    perspective: 1300px;
}

.ac-card {
    --anim-dur: 560ms;
    position: relative;
    overflow: hidden;
    border-radius: 1.25rem;
    box-shadow: 0 4px 18px -4px rgba(0, 0, 0, .55);
    will-change: transform, opacity, filter;
    min-width: 9rem;
    margin: 0.25rem;
}

.ac-card[aria-selected="true"] {
    box-shadow: 0 12px 38px -6px rgba(0, 0, 0, .65);
}

.ac-card::after {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at 50% 55%, rgba(255, 255, 255, 0.10), rgba(0, 0, 0, .55));
    opacity: 0;
    transition: opacity 400ms ease;
    pointer-events: none;
    mix-blend-mode: overlay;
}

.ac-card[aria-selected="true"]::after {
    opacity: .55;
}

.ac-card {
    transition: transform var(--anim-dur) cubic-bezier(.25, .9, .34, 1.31), opacity var(--anim-dur) ease-out, filter var(--anim-dur) ease-out, box-shadow var(--anim-dur) ease-out;
}

[data-orientation="vertical"] .ac-card {
    width: 100%;
}

@media (prefers-reduced-motion: reduce) {
    .ac-card {
        transition: none !important;
        animation: none !important;
    }
}

.ac-spinner {
    width: 2.5rem;
    height: 2.5rem;
    border: 3px solid rgba(255, 255, 255, .35);
    border-top-color: #fff;
    border-radius: 50%;
    animation: ac-spin 0.9s linear infinite;
}

@keyframes ac-spin {
    to {
        transform: rotate(360deg);
    }
}
</style>
