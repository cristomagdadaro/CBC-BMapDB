<script setup>
import {ref, computed, defineProps} from 'vue';
import AdvancedCarousel from '@/Components/Carousel/AdvancedCarousel.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    items: {
        type: Array,
        default: () => [
            {
                name: 'Rice',
                image: '/img/commodities/p-rice.webp',
                data: {varieties: 21, research: 323, breeders: 32},
                route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rice&geo_location_filter=region&with=breeder,location,characteristics,additionalinfo'
            },
            {
                name: 'Abaca',
                image: '/img/commodities/p-abaca.webp',
                data: {varieties: 12, research: 123, breeders: 23},
                route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region'
            },
            {
                name: 'Banana',
                image: '/img/commodities/p-banana.webp',
                data: {varieties: 12, research: 123, breeders: 23},
                route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region'
            },
            {
                name: 'Corn',
                image: '/img/commodities/p-corn.webp',
                data: {varieties: 54, research: 565, breeders: 122},
                route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Corn&geo_location_filter=region'
            },
            {
                name: 'Coconut',
                image: '/img/commodities/p-coconut.webp',
                data: {varieties: 12, research: 123, breeders: 23},
                route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region'
            },
            /*{
                name: 'Cotton',
                image: '/img/commodities/p-cotton.webp',
                data: {varieties: 12, research: 123, breeders: 23},
                route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Cotton&geo_location_filter=region'
            },
            {
                name: 'Tomato',
                image: '/img/commodities/p-tomato.webp',
                data: {varieties: 32, research: 234, breeders: 45},
                route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Tomato&geo_location_filter=region'
            },
            {
                name: 'Eggplant',
                image: '/img/commodities/p-eggplant.webp',
                data: {varieties: 23, research: 234, breeders: 45},
                route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Eggplant&geo_location_filter=region'
            },
            {
                name: 'Rubber',
                image: '/img/commodities/p-rubber.webp',
                data: {varieties: 12, research: 123, breeders: 23},
                route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region'
            },
            {
                name: 'Adlay',
                image: '/img/commodities/p-adlay.webp',
                data: {varieties: 12, research: 123, breeders: 23},
                route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region'
            },
            {
                name: 'Mango',
                image: '/img/commodities/p-mango.webp',
                data: {varieties: 12, research: 123, breeders: 23},
                route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region'
            },
            {
                name: 'Quinoa',
                image: '/img/commodities/p-quinoa.webp',
                data: {varieties: 12, research: 123, breeders: 23},
                route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region'
            },

            {
                name: 'Coffee',
                image: '/img/commodities/p-coffee.webp',
                data: {varieties: 12, research: 123, breeders: 23},
                route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region'
            },
            {
                name: 'Sugarcane',
                image: '/img/commodities/p-sugarcane.webp',
                data: {varieties: 12, research: 123, breeders: 23},
                route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region'
            },
            {
                name: 'Cacao',
                image: '/img/commodities/p-cacao.webp',
                data: {varieties: 12, research: 123, breeders: 23},
                route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region'
            },
            {
                name: 'Pineapple',
                image: '/img/commodities/p-pineapple.webp',
                data: {varieties: 12, research: 123, breeders: 23},
                route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region'
            },
            {
                name: 'Cassava',
                image: '/img/commodities/p-cassava.webp',
                data: {varieties: 12, research: 123, breeders: 23},
                route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region'
            },
            {
                name: 'Sweet Potato',
                image: '/img/commodities/p-sweetpotato.webp',
                data: {varieties: 12, research: 123, breeders: 23},
                route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region'
            },
            {
                name: 'Peanut',
                image: '/img/commodities/p-peanut.webp',
                data: {varieties: 12, research: 123, breeders: 23},
                route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region'
            },
            {
                name: 'Papaya',
                image: '/img/commodities/p-papaya.webp',
                data: {varieties: 12, research: 123, breeders: 23},
                route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region'
            },
            {
                name: 'Durian',
                image: '/img/commodities/p-durian.webp',
                data: {varieties: 12, research: 123, breeders: 23},
                route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region'
            },
            {
                name: 'Jackfruit',
                image: '/img/commodities/p-jackfruit.webp',
                data: {varieties: 12, research: 123, breeders: 23},
                route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region'
            },
            {
                name: 'Soybean',
                image: '/img/commodities/p-soybean.webp',
                data: {varieties: 12, research: 123, breeders: 23},
                route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region'
            },
            {
                name: 'Mungbean',
                image: '/img/commodities/p-mungbean.webp',
                data: {varieties: 12, research: 123, breeders: 23},
                route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region'
            },
            {
                name: 'Taro',
                image: '/img/commodities/p-taro.webp',
                data: {varieties: 12, research: 123, breeders: 23},
                route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region'
            },
            {
                name: 'Yam',
                image: '/img/commodities/p-yam.webp',
                data: {varieties: 12, research: 123, breeders: 23},
                route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region'
            },
            {
                name: 'Black Pepper',
                image: '/img/commodities/p-blackpepper.webp',
                data: {varieties: 12, research: 123, breeders: 23},
                route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region'
            },
            {
                name: 'Ginger',
                image: '/img/commodities/p-ginger.webp',
                data: {varieties: 12, research: 123, breeders: 23},
                route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region'
            },
            {
                name: 'Onion',
                image: '/img/commodities/p-onion.webp',
                data: {varieties: 12, research: 123, breeders: 23},
                route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region'
            },
            {
                name: 'Garlic',
                image: '/img/commodities/p-garlic.webp',
                data: {varieties: 12, research: 123, breeders: 23},
                route: route('projects.breedersmap.public') + '?is_exact=true&commodity=Rubber&geo_location_filter=region'
            },*/
        ]
    },
    autoAdvance: {type: Boolean, default: true},
    verticalBreakpoint: {type: Number, default: 640},
});

const itemCount = computed(() => props.items.length);
const currentItem = ref(null);
const activeItem = computed(() => currentItem.value || props.items[0] || null);

function onChange(item) {
    currentItem.value = item;
}
</script>

<template>
    <section class="py-6 lg:py-8" aria-labelledby="priority-commodities-heading">
        <div class="text-center mb-8 lg:mb-10">
            <span class="badge-primary mb-4 inline-flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V3m0 3a5 5 0 015 5m-5-5a5 5 0 00-5 5m5 7v3m0-3a5 5 0 01-5-5m5 5a5 5 0 005-5"/></svg>
                Featured Crops
            </span>
            <h2 id="priority-commodities-heading" class="text-3xl sm:text-4xl font-bold text-gray-900 font-display">{{ itemCount }} Priority Commodities</h2>
            <p class="text-gray-600 mt-2 max-w-2xl mx-auto">Browse highlighted commodities with key breeding, variety, and publication insights.</p>
        </div>

        <div class="overflow-x-hidden overflow-y-hidden">
        <AdvancedCarousel
            :items="props.items"
            :auto-advance="autoAdvance"
            :vertical-breakpoint="verticalBreakpoint"
            :max-display="5"
            :center-scale="1.20"
            :scale-falloff="0.35"
            :min-scale="0.55"
            :depth-step="60"
            :max-depth="220"
            :x-spread="14"
            :y-spread="18"
            :opacity-falloff="0.4"
            :min-opacity="0.25"
            :enable-blur="true"
            :blur-step="2"
            :max-blur="4"
            :swipe-threshold="30"
            :multiple-step-distance="80"
            :max-momentum-steps="4"
            :auto-advance-interval="2500"
            :interaction-idle-ms="3500"
            aspect-ratio="3/4"
            card-width="26rem"
            card-min-width="20rem"
            aria-label="Priority commodities carousel"
            @change="onChange"
        >
            <template #item="{ item, isCenter }">
                <div class="z-10 flex flex-col justify-end h-full p-6 pointer-events-none drop-shadow-xl">
                    <span :class="[
                        'font-bold text-white font-display leading-none',
                        isCenter ? 'text-2xl sm:text-3xl' : 'text-lg sm:text-xl opacity-80'
                    ]">
                        {{ item.name }}
                    </span>
                </div>
            </template>
        </AdvancedCarousel>
        </div>

        <div v-if="activeItem" class="mt-8 bg-white rounded-xl shadow-sm p-5 sm:p-6 hidden" aria-live="polite">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mb-4">
                <h3 class="text-xl font-semibold text-gray-900">{{ activeItem.name }}</h3>
                <Link :href="activeItem.route" class="btn-secondary text-sm py-2 px-4 inline-flex items-center justify-center">
                    View commodity data
                </Link>
            </div>

            <div class="grid grid-cols-3 gap-3 sm:gap-4">
                <div class="bg-pin-gray rounded-lg p-3 text-center">
                    <div class="text-xl sm:text-2xl font-bold text-pin-green font-display">{{ activeItem.data.breeders }}</div>
                    <div class="text-xs sm:text-sm text-gray-600 mt-1">Breeders</div>
                </div>
                <div class="bg-pin-gray rounded-lg p-3 text-center">
                    <div class="text-xl sm:text-2xl font-bold text-pin-green font-display">{{ activeItem.data.varieties }}</div>
                    <div class="text-xs sm:text-sm text-gray-600 mt-1">Varieties</div>
                </div>
                <div class="bg-pin-gray rounded-lg p-3 text-center">
                    <div class="text-xl sm:text-2xl font-bold text-pin-green font-display">{{ activeItem.data.research }}</div>
                    <div class="text-xs sm:text-sm text-gray-600 mt-1">Publications</div>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
/* Page-specific styling can live here if needed */
</style>
