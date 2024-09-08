<script>
import PageLayout from "@/Layouts/PageLayout.vue";
import { CBCProjectsPublic } from "@/Pages/constants.ts";
import { Link, Head } from '@inertiajs/vue3';
import PhilippineMapOutline from "@/Components/Icons/PhilippineMapOutline.vue";
import BmCollaborators from "@/Pages/Projects/BreedersMap/presentation/components/colaborators/BmCollaborators.vue";
import TransitionContainer from "@/Components/CustomDropdown/Components/TransitionContainer.vue";
import ArrowLeft from "@/Components/Icons/ArrowLeft.vue";
import ArrowRight from "@/Components/Icons/ArrowRight.vue";

export default {
    components: {
        ArrowRight,
        ArrowLeft,
        TransitionContainer,
        BmCollaborators,
        PageLayout,
        Link,
        Head,
        PhilippineMapOutline,
    },
    setup() {
        return {
            CBCProjectsPublic,
        };
    },
    data() {
        return {
            hoveredData: null,
            containerRef: null,
            maxDisplay: 5,
            currentCommodity: null,
            scrolledCommodities: [],
            commodities: [
                {
                    name: 'Rice',
                    image: '/img/commodities/p-rice.png',
                    data: {
                        'varieties': 21,
                        'research': 323,
                        'breeders': 32,
                    }
                },
                {
                    name: 'Corn',
                    image: '/img/commodities/p-corn.png',
                    data: {
                        'varieties': 54,
                        'research': 565,
                        'breeders': 122,
                    }
                },
                {
                    name: 'Cotton',
                    image: '/img/commodities/p-cotton.png',
                    data: {
                        'varieties': 12,
                        'research': 123,
                        'breeders': 23,
                    }
                },
                {
                    name: 'Tomato',
                    image: '/img/commodities/p-tomato.png',
                    data: {
                        'varieties': 32,
                        'research': 234,
                        'breeders': 45,
                    }
                },
                {
                    name: 'Eggplant',
                    image: '/img/commodities/p-eggplant.png',
                    data: {
                        'varieties': 23,
                        'research': 234,
                        'breeders': 45,
                    }
                },
                {
                    name: 'Rubber',
                    image: '/img/commodities/p-rubber.png',
                    data: {
                        'varieties': 12,
                        'research': 123,
                        'breeders': 23,
                    }
                }
            ]
        };
    },
    methods: {
        handleInfiniteScroll($event = null) {
            const delta = $event ? $event.deltaY || $event.detail || $event.wheelDelta : 0;
            const centerIndex = Math.floor(this.maxDisplay / 2);

            if (delta < 0) {
                const temp = this.scrolledCommodities.shift();
                this.scrolledCommodities.push(temp);
            } else {
                const temp = this.scrolledCommodities.pop();
                this.scrolledCommodities.unshift(temp);
            }

            this.currentCommodity = this.scrolledCommodities[centerIndex].name;
        }

    },
    computed: {
        currentCommodityData() {
            return this.commodities.find(commodity => commodity.name === this.currentCommodity);
        }
    },
    mounted() {
        this.scrolledCommodities = this.commodities;
        this.handleInfiniteScroll();
        const container = this.$refs.containerRef;
        //hide the scroll
        container.style.overflow = 'hidden';
    }
};
</script>

<template>
    <Head title="Databases" />
    <page-layout :is-wide-display="true">
        <div class="flex flex-col gap-5 text-gray-700 p-2 overflow-x-hidden">
            <div class="text-gray-100 bg-cbc-olive-green flex flex-col sm:gap-1 gap-3 sm:p-5 p-8 sm:text-left text-center drop-shadow-lg rounded-md">
                <p class="font-medium sm:text-3xl text-xl sm:leading-relaxed leading-tight">
                    Welcome to
                    <br class="block sm:hidden" />
                    <span class="text-cbc-dark-green">C</span>rop <span class="text-cbc-dark-green">B</span>iotech
                    <span class="text-cbc-dark-green">C</span>entralized <span class="text-cbc-dark-green">D</span>atabase
                </p>
                <p class="leading-relaxed sm:text-left text-justify sm:text-lg text-sm">
                    This specialized online platform offers a centralized repository of essential information meticulously curated to support your crop biotechnology research endeavors. Within this digital resource, you will find a comprehensive collection of data, tools, and resources designed to facilitate your scientific investigations, accelerate discoveries, and drive innovation in the field of crop biotechnology.
                </p>
                <div class="flex flex-wrap gap-2 p-2 sm:justify-start justify-center z-[999]">
                    <Link v-for="project in CBCProjectsPublic" :href="route(project.value)" class="shadow-md hover:bg-gray-100 hover:text-gray-900 text-gray-100 bg-cbc-dark-green px-5 py-3 rounded">
                        {{ project.label }}
                    </Link>
                </div>
            </div>
            <div>
                <div class="my-20">
                    <h3 class="text-center select-none lg:text-[3rem] md:text-[2.2rem] sm:text-[1.7rem] text-[1rem] font-bold text-gray-900 drop-shadow">
                        Breeders' Map Overview
                    </h3>
                    <div class="flex flex-col-reverse sm:flex-row justify-between items-center gap-5 text-center sm:text-left">
                        <div v-if="hoveredData" class="select-none lg:text-[3rem] md:text-[2.2rem] sm:text-[1.7rem] text-[1rem] font-normal sm:font-bold text-gray-900 drop-shadow">
                            {{ hoveredData.province }} <br /> has a total of <br /> {{ hoveredData.data }} biotechnology <br /> related commodities
                        </div>
                        <div v-else class="lg:text-[3rem] md:text-[2.2rem] sm:text-[1.7rem] text-[1rem] font-normal text-gray-900 drop-shadow">
                            Hover over a region to see data.
                        </div>
                        <philippine-map-outline class="max-w-[15rem] sm:max-w-[30vw] min-w-[7rem] drop-shadow-lg" @hovered="hoveredData = $event"/>
                    </div>
                </div>

                <div class="my-20 select-none">
                    <h3 class="text-center select-none lg:text-[3rem] md:text-[2.2rem] sm:text-[1.7rem] text-[1rem] font-bold text-gray-900 drop-shadow">
                        Priority Commodities
                    </h3>
                    <p class="text-center text-gray-900 font-normal text-lg">
                        The following are the priority commodities for the Crop Biotechnology Center:
                    </p>

                    <div ref="containerRef" class="relative flex flex-row justify-center overflow-x-auto my-2 w-full min-h-[20rem] max-h-[20rem]">
                        <div class="flex sm:hidden text-gray-100 absolute z-30 h-full justify-between w-full ">
                            <arrow-left class="w-16 h-auto" @click="handleInfiniteScroll(null)" />
                            <arrow-right class="w-16 h-auto" @click="handleInfiniteScroll(null)" />
                        </div>
                        <transition-container v-for="commodity in scrolledCommodities" >
                        <div v-if="scrolledCommodities.indexOf(commodity) < maxDisplay" @wheel.prevent="handleInfiniteScroll($event)" :class="commodity.name === currentCommodity?'scale-110 mx-0 shadow-md brightness-100':'scale-90 mx-0 brightness-50'" class="bg-cbc-dark-green sm:min-w-[20vw] min-w-[15rem] min-h-auto text-gray-100 relative duration-300 z-10">
                            <img :src="commodity.image" :alt="commodity.name" class="absolute inset-0 w-full h-full object-cover rounded" />
                            <div class="relative z-10 flex items-center justify-center h-full drop-shadow-2xl text-xl font-bold">
                                {{ commodity.name }}
                            </div>
                        </div>
                        </transition-container>
                    </div>
                    <div v-if="currentCommodityData" class="flex flex-col justify-center text-gray-900 font-normal text-lg">
                        <div class="flex gap-2 justify-center ">
                            <span class="font-bold">Research Studies</span>
                            <span>{{ currentCommodityData.data.research }}</span>
                        </div>
                        <div class="flex gap-2 justify-center ">
                            <span class="font-bold">Breeders</span>
                            <span>{{ currentCommodityData.data.breeders }}</span>
                        </div>
                        <div class="flex gap-2 justify-center ">
                            <span class="font-bold">Varieties</span>
                            <span>{{ currentCommodityData.data.varieties }}</span>
                        </div>
                    </div>
                </div>
                <bm-collaborators />
            </div>
        </div>
    </page-layout>
</template>

<style scoped>
/* Ensure the container and items are styled correctly for horizontal scrolling */
.flex-row {
    display: flex;
    flex-direction: row;
    overflow-x: auto;
    scroll-behavior: smooth;
    padding-left: 1rem; /* Add padding to the left */
    padding-right: 1rem; /* Add padding to the right */
}

.flex-row::-webkit-scrollbar {
    display: none; /* Hide scrollbar for better UX */
}

.flex-row {
    -ms-overflow-style: none;  /* IE and Edge */
    scrollbar-width: none;  /* Firefox */
}
</style>
