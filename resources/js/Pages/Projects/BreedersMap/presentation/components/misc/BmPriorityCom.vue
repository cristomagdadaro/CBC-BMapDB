<script setup>
import {ref, reactive, computed, onMounted, onBeforeUnmount, watch, nextTick, defineProps, defineEmits, defineExpose} from 'vue';
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
      { name: 'Rubber', image: '/img/commodities/p-rubber.webp', data: { varieties: 12, research: 123, breeders: 23 }, route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region' }
    ]
  },
  maxDisplay: { type: Number, default: 5 },
  centerScale: { type: Number, default: 1.30 },
  scaleFalloff: { type: Number, default: 0.35 },
  minScale: { type: Number, default: 0.45 },
  depthStep: { type: Number, default: 60 },
  maxDepth: { type: Number, default: 220 },
  xSpread: { type: Number, default: 14 },
  opacityFalloff: { type: Number, default: 0.5 },
  minOpacity: { type: Number, default: 0.15 },
  enableBlur: { type: Boolean, default: true },
  blurStep: { type: Number, default: 2 },
  maxBlur: { type: Number, default: 4 },
  swipeThreshold: { type: Number, default: 30 },
  multipleStepDistance: { type: Number, default: 80 },
  maxMomentumSteps: { type: Number, default: 4 },
  autoAdvance: { type: Boolean, default: true },
  autoAdvanceInterval: { type: Number, default: 5000 },
  interactionIdleMs: { type: Number, default: 3500 },
  placeholderImage: { type: String, default: 'data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///ywAAAAAAQABAAACAUwAOw==' },
  animationDurationMs: { type: Number, default: 520 },
  pulseDurationMs: { type: Number, default: 680 },
  emitInitial: { type: Boolean, default: true },
});

const emit = defineEmits(['change']);

// State
const containerRef = ref(null);
const headIndex = ref(0);
const direction = ref('forward'); // 'forward' | 'backward'
const currentName = ref(null);

// Interaction state
const isPaused = ref(false);
const isInteracting = ref(false);
let interactionIdleTimer = null;
let autoAdvanceTimer = null;

// Touch state for momentum
const touch = reactive({
  startX: null,
  startY: null,
  startTime: 0,
  positions: [],
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
  if (e.key === 'ArrowRight') { e.preventDefault(); nextItem(); }
  else if (e.key === 'ArrowLeft') { e.preventDefault(); prevItem(); }
  else if (e.key === 'Home') { e.preventDefault(); headIndex.value = 0; }
  else if (e.key === 'End') { e.preventDefault(); headIndex.value = (itemCount.value - effectiveDisplay.value + itemCount.value) % itemCount.value; }
}

// Touch with inertia
function onTouchStart(e) {
  if (!e.touches?.length) return;
  markInteraction();
  const t = e.touches[0];
  touch.startX = t.clientX;
  touch.startY = t.clientY;
  touch.startTime = performance.now();
  touch.positions = [{ x: t.clientX, t: touch.startTime }];
}
function onTouchMove(e) {
  if (!e.touches?.length || touch.startX === null) return;
  const t = e.touches[0];
  const dx = t.clientX - touch.startX;
  const dy = t.clientY - touch.startY;
  const now = performance.now();
  touch.positions.push({ x: t.clientX, t: now });
  if (touch.positions.length > 6) touch.positions.shift();
  if (Math.abs(dx) > Math.abs(dy)) e.preventDefault();
}
function onTouchEnd(e) {
  if (touch.startX === null) return;
  const t = e.changedTouches?.[0];
  if (!t) return;
  const dx = t.clientX - touch.startX;
  const totalTime = performance.now() - touch.startTime;
  // Velocity
  let velocity = 0;
  if (touch.positions.length >= 2) {
    const first = touch.positions[0];
    const last = touch.positions[touch.positions.length - 1];
    velocity = (last.x - first.x) / (last.t - first.t || 1); // px per ms
  } else {
    velocity = dx / (totalTime || 1);
  }
  const absDx = Math.abs(dx);
  let steps = 0;
  if (absDx > props.swipeThreshold) {
    const distanceSteps = Math.floor((absDx - props.swipeThreshold) / props.multipleStepDistance) + 1;
    const absVel = Math.abs(velocity);
    let velBonus = 0;
    if (absVel > 1.4) velBonus = 2; else if (absVel > 0.9) velBonus = 1;
    steps = Math.min(props.maxMomentumSteps, distanceSteps + velBonus);
  }
  if (steps > 0) {
    const sign = dx < 0 ? 1 : -1; // left -> forward
    advanceBy(sign * steps);
  } else if (absDx > props.swipeThreshold) {
    advanceBy(dx < 0 ? 1 : -1);
  }
  touch.startX = touch.startY = null;
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

// Style computation for each visible card
function getItemStyle(idx, item) {
  const offset = idx - centerIndex.value; // 0 is center
  const abs = Math.abs(offset);
  const scaleBase = props.centerScale - abs * props.scaleFalloff;
  const scale = Math.max(props.minScale, scaleBase);
  const translateZ = -Math.min(abs * props.depthStep, props.maxDepth);
  const translateX = offset * -props.xSpread; // opposite directional parallax
  let opacity = 1 - abs * props.opacityFalloff;
  opacity = Math.max(props.minOpacity, opacity);
  let brightness = offset === 0 ? 1 : Math.max(0.25, 0.55 - (abs - 1) * 0.15);
  let saturation = offset === 0 ? 1.15 : 0.55;
  let blur = props.enableBlur && abs > 1 ? Math.min((abs - 1) * props.blurStep, props.maxBlur) : 0;
  return {
    '--offset': offset,
    'z-index': 300 - abs * 10,
    transform: `translate3d(${translateX}%,0,${translateZ}px) scale(${scale})`,
    opacity,
    filter: `brightness(${brightness}) saturate(${saturation}) blur(${blur}px)`
  };
}

function onMouseEnter() { pause(true); }
function onMouseLeave() { pause(false); }
function onFocusIn() { pause(true); }
function onFocusOut() { pause(false); }

onMounted(() => {
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
});

// Expose imperative API
function play() { pause(false); }
function prev() { prevItem(); }
function next() { nextItem(); }
function goTo(name) {
  const idx = props.items.findIndex(i => i.name === name);
  if (idx === -1) return;
  // shift headIndex so that desired becomes center
  const targetIndexInItems = idx;
  const desiredHead = (targetIndexInItems - centerIndex.value + itemCount.value) % itemCount.value;
  headIndex.value = desiredHead;
  updateCurrentCenter();
}

defineExpose({ play, pause, prev, next, goTo });
</script>

<template>
  <div class="overflow-x-hidden overflow-y-hidden py-5">
    <h3 class="text-center text-subtitle">Priority Commodities</h3>
    <p class="text-normal text-center text-dark-color">Currently, the center has identified {{ itemCount }} priority commodities</p>
    <div
      ref="containerRef"
      class="relative select-none flex flex-row justify-center my-2 w-full min-h-[10rem] sm:min-h-[20rem] max-h-[10rem] sm:max-h-[20rem] perspective-1000 focus:outline-none drop-shadow"
      role="listbox"
      :aria-activedescendant="currentName ? sanitizeId(currentName) : null"
      tabindex="0"
      aria-label="Priority commodities carousel"
      @wheel.prevent="onWheel"
      @touchstart.passive="onTouchStart"
      @touchmove="onTouchMove"
      @touchend="onTouchEnd"
      @keydown="onKeyDown"
      @mouseenter="onMouseEnter" @mouseleave="onMouseLeave"
      @focusin="onFocusIn" @focusout="onFocusOut"
    >
      <!-- Mobile arrows -->
      <div class="flex sm:hidden text-gray-100 absolute z-30 h-full justify-between w-full pointer-events-none" aria-label="Carousel navigation controls">
        <ArrowLeft class="w-16 h-auto pointer-events-auto" @click="prevItem" />
        <ArrowRight class="w-16 h-auto pointer-events-auto" @click="nextItem" />
      </div>

      <!-- Replaced transition-group with simple flex container to avoid FLIP transform override -->
      <div class="flex flex-row gap-0 items-stretch will-change-transform">
        <div
          v-for="(item, idx) in visibleItems" :key="item.name"
          class="carousel-card relative bg-cbc-dark-green sm:min-w-[20vw] min-w-[10rem] text-gray-100 duration-300"
          :id="sanitizeId(item.name)"
          role="option"
          :aria-selected="item.name === currentName"
          :data-name="item.name"
          :style="getItemStyle(idx, item)"
          :ref="el => setItemRef(item.name, el)"
        >
          <Link :href="item.route" class="absolute inset-0">
            <img :src="getImageSrc(item)" loading="lazy" :alt="item.name" class="absolute inset-0 w-full h-full object-cover rounded transition-opacity duration-300" :class="{'opacity-0': !loaded.has(item.name), 'opacity-100': loaded.has(item.name)}" />
            <div class="relative z-10 flex items-center justify-center h-full text-xl font-bold pointer-events-none drop-shadow">
              {{ item.name }}
            </div>
          </Link>
        </div>
      </div>
    </div>
    <div v-if="currentItem" class="flex hidden flex-col justify-center text-gray-900 text-normal" aria-live="polite">
      <div class="flex gap-2 justify-center"><span class="font-bold">Breeders</span><span>{{ currentItem.data.breeders }}</span></div>
      <div class="flex gap-2 justify-center"><span class="font-bold">Varieties</span><span>{{ currentItem.data.varieties }}</span></div>
      <div class="flex gap-2 justify-center"><span class="font-bold">Publications</span><span>{{ currentItem.data.research }}</span></div>
    </div>
  </div>
</template>

<style scoped>
/* Removed transition-group specific fade classes to prevent transform interference */
/* .carousel-fade-* classes deleted */

/* Core styling retained */
.perspective-1000 { perspective: 1200px; }
.carousel-card { position: relative; overflow: hidden; border-radius: .4rem; box-shadow: 0 4px 12px -2px rgba(0,0,0,.35); }
.carousel-card[aria-selected="true"] { box-shadow: 0 10px 32px -4px rgba(0,0,0,.6); }
.carousel-card::after { content: ''; position: absolute; inset:0; background: radial-gradient(circle at center, rgba(0,0,0,0) 40%, rgba(0,0,0,.4)); opacity: 0; transition: opacity 400ms ease; pointer-events:none; }
.carousel-card[aria-selected="true"]::after { opacity: 1; }

.carousel-card { transition: transform var(--anim-dur,520ms) cubic-bezier(.22,.72,.23,.99),
                             opacity var(--anim-dur,520ms) cubic-bezier(.22,.72,.23,.99),
                             filter var(--anim-dur,520ms) cubic-bezier(.22,.72,.23,.99),
                             box-shadow var(--anim-dur,520ms) cubic-bezier(.22,.72,.23,.99); }

@media (prefers-reduced-motion: reduce) {
  .carousel-card { transition: none !important; animation: none !important; }
}
</style>
