<script>
import ArrowLeft from "@/Components/Icons/ArrowLeft.vue";
import ArrowRight from "@/Components/Icons/ArrowRight.vue";

export default {
    name: "BmPriorityCom",
    components: {ArrowRight, ArrowLeft},
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
    },
    data() {
        return {
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
}
</script>

<template>
    <div class="overflow-y-hidden overflow-x-hidden py-5 cursor-scroll">
        <h3 class="text-center text-subtitle">
            Priority Commodities
        </h3>
        <p class="text-normal text-center text-dark-color">
            Currently, the center has identified {{ commodities.length }} priority commodities
        </p>
        <div ref="containerRef" class="drop-shadow relative select-none flex flex-row justify-center overflow-x-auto my-2 w-full min-h-[10rem] sm:min-h-[20rem] max-h-[10rem] sm:max-h-[20rem]">
            <div class="flex sm:hidden text-gray-100 absolute z-30 h-full justify-between w-full ">
                <arrow-left class="w-16 h-auto" @click="handleInfiniteScroll(null)" />
                <arrow-right class="w-16 h-auto" @click="handleInfiniteScroll(null)" />
            </div>
            <template v-for="commodity in scrolledCommodities">
                <div v-if="scrolledCommodities.indexOf(commodity) < maxDisplay" @wheel.prevent="handleInfiniteScroll($event)" :class="commodity.name === currentCommodity?'scale-110 mx-0 shadow-md brightness-100':'scale-90 mx-0 brightness-50'" class="bg-cbc-dark-green sm:min-w-[20vw] min-w-[10rem] text-gray-100 relative duration-300 z-10">
                    <img :src="commodity.image" :alt="commodity.name" class="absolute inset-0 w-full h-full object-cover rounded" />
                    <div class="relative z-10 flex items-center justify-center h-full drop-shadow shadow-lg text-xl font-bold">
                        {{ commodity.name }}
                    </div>
                </div>
            </template>
        </div>
        <div v-if="currentCommodityData" class="flex flex-col justify-center text-gray-900 text-normal">
            <div class="flex gap-2 justify-center ">
                <span class="font-bold">Breeders</span>
                <span>{{ currentCommodityData.data.breeders }}</span>
            </div>
            <div class="flex gap-2 justify-center ">
                <span class="font-bold">Varieties</span>
                <span>{{ currentCommodityData.data.varieties }}</span>
            </div>
            <div class="flex gap-2 justify-center ">
                <span class="font-bold">Publications</span>
                <span>{{ currentCommodityData.data.research }}</span>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
