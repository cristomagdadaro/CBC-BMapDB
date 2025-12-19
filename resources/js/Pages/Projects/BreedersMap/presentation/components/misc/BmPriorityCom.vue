<script setup>
import {ref, computed, defineProps} from 'vue';
import AdvancedCarousel from '@/Components/Carousel/AdvancedCarousel.vue';
import ArrowLeft from '@/Components/Icons/ArrowLeft.vue';
import ArrowRight from '@/Components/Icons/ArrowRight.vue';

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

function onChange(item) {
    currentItem.value = item;
}
</script>

<template>
    <div class="overflow-x-hidden overflow-y-hidden py-5">
        <h3 class="text-center text-subtitle">Priority Commodities</h3>
        <p class="text-normal pb-5 text-center text-dark-color">Currently, the center has identified {{ itemCount }} priority commodities</p>

        <AdvancedCarousel
            :items="props.items"
            :auto-advance="autoAdvance"
            :vertical-breakpoint="verticalBreakpoint"
            :max-display="5"
            :center-scale="1.30"
            :scale-falloff="0.35"
            :min-scale="0.45"
            :depth-step="60"
            :max-depth="220"
            :x-spread="14"
            :y-spread="18"
            :opacity-falloff="0.5"
            :min-opacity="0.15"
            :enable-blur="true"
            :blur-step="2"
            :max-blur="4"
            :swipe-threshold="30"
            :multiple-step-distance="80"
            :max-momentum-steps="4"
            :auto-advance-interval="2000"
            :interaction-idle-ms="3500"
            aspect-ratio="3/4"
            card-width="30rem"
            card-min-width="30rem"
            aria-label="Priority commodities carousel"
            @change="onChange"
        >
            <template #item="{ item, isCenter }">
                <div class="z-10 flex items-center justify-center h-full font-bold pointer-events-none drop-shadow-xl">
                    <span :class="isCenter ? 'text-xl sm:text-2xl' : 'text-base sm:text-xl opacity-70'">{{
                            item.name
                        }}</span>
                </div>
            </template>
        </AdvancedCarousel>

        <div v-if="currentItem" class="flex hidden flex-col justify-center text-gray-900 text-normal"
             aria-live="polite">
            <div class="flex gap-2 justify-center"><span
                class="font-bold">Breeders</span><span>{{ currentItem.data.breeders }}</span></div>
            <div class="flex gap-2 justify-center"><span
                class="font-bold">Varieties</span><span>{{ currentItem.data.varieties }}</span></div>
            <div class="flex gap-2 justify-center"><span
                class="font-bold">Publications</span><span>{{ currentItem.data.research }}</span></div>
        </div>
    </div>
</template>

<style scoped>
/* Page-specific styling can live here if needed */
</style>
