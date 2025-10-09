<script setup>
import { computed } from 'vue';

defineOptions({ name: 'GreenWaves' });

const props = defineProps({
  glowColor: { type: String, default: '#efc02d' },
  baseStrokeWidth: { type: [Number, String], default: 1.25 },
  baseOpacity: { type: Number, default: 0.1 },
  haloStrokeWidth: { type: [Number, String], default: 1.5 },
  haloOpacity: { type: Number, default: 0.22 },
  pulseStrokeWidth: { type: [Number, String], default: 3.2 },
  dashArray1: { type: String, default: '240 520' },
  dashArray2: { type: String, default: '140 380' },
  pulse2Delay: { type: String, default: '-1.2s' },
  durationTop: { type: String, default: '6.5s' },
  durationBottom: { type: String, default: '7.2s' },
  blurStdDev: { type: [Number, String], default: 5 },
  enableHalo: { type: Boolean, default: true },
  enablePulse2: { type: Boolean, default: true },
  mixBlendMode: { type: String, default: 'screen' },
});

const cssVars = computed(() => ({
  '--glow-color': props.glowColor,
  '--base-width': String(props.baseStrokeWidth),
  '--base-opacity': String(props.baseOpacity),
  '--halo-width': String(props.haloStrokeWidth),
  '--halo-opacity': String(props.haloOpacity),
  '--pulse-width': String(props.pulseStrokeWidth),
  '--dasharray1': props.dashArray1,
  '--dasharray2': props.dashArray2,
  '--pulse2-delay': props.pulse2Delay,
  '--dur-top': props.durationTop,
  '--dur-bot': props.durationBottom,
  '--blend-mode': props.mixBlendMode,
}));
</script>

<template>
    <div class="green-waves drop-shadow-lg relative z-1 max-w-screen">
        <svg class="rotate-180 absolute top-0 pointer-events-none w-full" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" viewBox="0 0 1439 560" :style="cssVars">
            <defs>
                <filter id="soft-glow" x="-50%" y="-50%" width="200%" height="200%">
                    <feGaussianBlur :stdDeviation="blurStdDev" result="blur" />
                    <feMerge>
                        <feMergeNode in="blur" />
                        <feMergeNode in="SourceGraphic" />
                    </feMerge>
                </filter>
            </defs>

            <!-- Filled waves (existing) -->
            <g fill="none">
                <rect width="1439" height="560" x="0" y="0"></rect>
                <path fill="rgba(7, 152, 84, 1)" d="M 0,258 C 57.6,215 172.8,61.2 288,43 C 403.2,24.8 460.8,170.4 576,167 C 691.2,163.6 748.8,14.6 864,26 C 979.2,37.4 1037,213.2 1152,224 C 1267,234.8 1381.6,108.8 1439,80L1439 560L0 560z" ></path>
            </g>
            <g fill="none">
                <rect width="1439" height="560" x="0" y="0"></rect>
                <path fill="rgba(0, 104, 55, 1)" d="M 0,390 C 96,408.8 288,479.6 480,484 C 672,488.4 768.2,411.2 960,412 C 1151.8,412.8 1343.2,472.8 1439,488L1439 560L0 560z"></path>
            </g>

            <!-- Glowing edge strokes overlay -->
            <g class="glow-layer" fill="none" stroke-linecap="round" pointer-events="none">
                <!-- Top wave edge: base + halo + pulses -->
                <path class="edge-stroke base top" d="M 0,258 C 57.6,215 172.8,61.2 288,43 C 403.2,24.8 460.8,170.4 576,167 C 691.2,163.6 748.8,14.6 864,26 C 979.2,37.4 1037,213.2 1152,224 C 1267,234.8 1381.6,108.8 1439,80" />
                <path v-if="enableHalo" class="edge-stroke halo top" d="M 0,258 C 57.6,215 172.8,61.2 288,43 C 403.2,24.8 460.8,170.4 576,167 C 691.2,163.6 748.8,14.6 864,26 C 979.2,37.4 1037,213.2 1152,224 C 1267,234.8 1381.6,108.8 1439,80" />
                <path class="edge-stroke pulse top" d="M 0,258 C 57.6,215 172.8,61.2 288,43 C 403.2,24.8 460.8,170.4 576,167 C 691.2,163.6 748.8,14.6 864,26 C 979.2,37.4 1037,213.2 1152,224 C 1267,234.8 1381.6,108.8 1439,80" />
                <path v-if="enablePulse2" class="edge-stroke pulse2 top" d="M 0,258 C 57.6,215 172.8,61.2 288,43 C 403.2,24.8 460.8,170.4 576,167 C 691.2,163.6 748.8,14.6 864,26 C 979.2,37.4 1037,213.2 1152,224 C 1267,234.8 1381.6,108.8 1439,80" />

                <!-- Bottom wave edge: base + halo + pulses -->
                <path class="edge-stroke base bottom" d="M 0,390 C 96,408.8 288,479.6 480,484 C 672,488.4 768.2,411.2 960,412 C 1151.8,412.8 1343.2,472.8 1439,488" />
                <path v-if="enableHalo" class="edge-stroke halo bottom" d="M 0,390 C 96,408.8 288,479.6 480,484 C 672,488.4 768.2,411.2 960,412 C 1151.8,412.8 1343.2,472.8 1439,488" />
                <path class="edge-stroke pulse bottom" d="M 0,390 C 96,408.8 288,479.6 480,484 C 672,488.4 768.2,411.2 960,412 C 1151.8,412.8 1343.2,472.8 1439,488" />
                <path v-if="enablePulse2" class="edge-stroke pulse2 bottom" d="M 0,390 C 96,408.8 288,479.6 480,484 C 672,488.4 768.2,411.2 960,412 C 1151.8,412.8 1343.2,472.8 1439,488" />
            </g>
        </svg>
    </div>
</template>

<style scoped>
/* Provide defaults for CSS variables so static analysis can resolve them */
.green-waves {
  --glow-color: #efc02d;
  --base-width: 1.25;
  --base-opacity: 0.1;
  --halo-width: 1.5;
  --halo-opacity: 0.22;
  --pulse-width: 3.2;
  --dasharray1: 240 520;
  --dasharray2: 140 380;
  --pulse2-delay: -1.2s;
  --dur-top: 6.5s;
  --dur-bot: 7.2s;
  --blend-mode: screen;
}

/* Base edge strokes using CSS variables for customization */
.edge-stroke.base.top,
.edge-stroke.base.bottom {
  stroke: var(--glow-color, #efc02d);
  stroke-width: var(--base-width, 1.25);
  opacity: var(--base-opacity, 0.1);
  filter: url(#soft-glow);
}

/* Soft outer halo */
.edge-stroke.halo {
  stroke: var(--glow-color, #efc02d);
  stroke-width: var(--halo-width, 1.5);
  opacity: var(--halo-opacity, 0.22);
  filter: url(#soft-glow);
}

/* Animated glowing pulses */
.edge-stroke.pulse,
.edge-stroke.pulse2 {
  stroke: var(--glow-color, #efc02d);
  stroke-width: var(--pulse-width, 3.2);
  filter: url(#soft-glow);
  animation: wave-glow linear infinite;
}
/* Primary pulse */
.edge-stroke.pulse { stroke-dasharray: var(--dasharray1, 240 520); }
/* Secondary pulse */
.edge-stroke.pulse2 { stroke-dasharray: var(--dasharray2, 140 380); animation-delay: var(--pulse2-delay, -1.2s); }

/* Speed per edge */
.edge-stroke.pulse.top, .edge-stroke.pulse2.top { animation-duration: var(--dur-top, 6.5s); }
.edge-stroke.pulse.bottom, .edge-stroke.pulse2.bottom { animation-duration: var(--dur-bot, 7.2s); }

/* Respect reduced motion preferences */
@media (prefers-reduced-motion: reduce) {
  .edge-stroke.pulse, .edge-stroke.pulse2 { animation: none !important; }
}

/* Blend glows above fills; customizable */
.glow-layer { mix-blend-mode: var(--blend-mode, screen); }

/* Infinite flow along the path */
@keyframes wave-glow {
  from { stroke-dashoffset: 0; }
  to   { stroke-dashoffset: -1080; }
}
</style>
