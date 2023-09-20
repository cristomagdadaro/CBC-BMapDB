<script>
import CloseIcon from '@/Components/Icons/Close.vue';
import ArrowRight from '@/Components/Icons/ArrowRight.vue';
import ArrowLeft from '@/Components/Icons/ArrowLeft.vue';
import CarouselIndicator from '@/Components/Modal/CarouselIndicator.vue';

export default{
    components: {
        CloseIcon,
        ArrowRight,
        ArrowLeft,
        CarouselIndicator,
    },
    data() {
        return {
            modalData: [
                {
                    image: '/img/sample1.jpg',
                    alternative: 'Sample Image 1',
                    show: true,
                    url: '/',
                },{
                    image: '/img/sample2.jpg',
                    alternative: 'Sample Image 2',
                    show: false,
                    url: false,
                },{
                    image: '/img/sample3.jpg',
                    alternative: 'Sample Image 3',
                    show: false,
                    url: false,
                },{
                    image: '/img/sample4.jpg',
                    alternative: 'Sample Image 4',
                    show: false,
                    url: false,
                }
            ],
            showModal: false, /* Shows the welcome modal by default */
        };
    },
    methods: {
        close() {
            this.showModal = false;
        },
        /* A function that will show the next image in modalData */
        next() {
            /* Get the index of the image that is currently shown */
            let index = this.modalData.findIndex(data => data.show);            /* Hide the current image */
            this.modalData[index].show = false;
            /* when at the end go back to first image */
            if(index == this.modalData.length-1) {
                this.modalData[0].show = true;
            }else{
                /* Show the next image */
                this.modalData[index+1].show = true;
            }
        },
        /* A function that will show the previous image in modalData */
        prev() {
            /* Get the index of the image that is currently shown */
            let index = this.modalData.findIndex(data => data.show);
            /* Hide the current image */
            this.modalData[index].show = false;
            /* when at the start go back to last image */
            if(index == 0) {
                this.modalData[this.modalData.length-1].show = true;
            }else{
                /* Show the previous image */
                this.modalData[index-1].show = true;
            }
        },
    },
    mounted() {
        /* A function that will show the next image in modalData every 5 seconds but resets the countdown to 0 when next or prev is triggered */
        
    },
}

</script>
<template>
    <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-75"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95">
        <div v-show="showModal" class="flex absolute min-h-screen min-w-full justify-center items-center z-50 backdrop-blur-sm backdrop-brightness-75">
            <div class="relative border-4 border-white max-h-[90%] max-w-[90%] sm:max-h-[50%] sm:max-w-[50%] aspect-video">
                <button class="h-6 w-auto active:scale-90 text-white top-0 right-0 absolute m-1 hover:scale-110 hover:rotate-180 duration-300 z-10" @click="close()" title="Close modal"><CloseIcon class="sm:h-5 h-3 w-auto rounded-md bg-cbc-dark-green"/></button>
                <div class="flex flex-row relative">
                    <button @click="prev()" class="ml-1 absolute top-[50%]"><ArrowLeft class="rounded-full bg-cbc-dark-green h-6 w-auto active:scale-90 duration-200 text-white" /></button>
                    <div class="flex-row flex">
                        <template v-for="data in modalData">
                            <img :src="data.image" :alt="data.alternative" class="object-cover" :class="data.show?'block':'hidden'">
                            <a :href="data.url" :class="data.url?'block':'hidden'" class="bottom-1 right-1 absolute m-1 hover:bg-cbc-yellow-green duration-300 bg-cbc-dark-green px-2 rounded-md text-xl text-[#fff] z-10">Read More</a>
                        </template>
                    </div>
                    <div class="absolute flex gap-2 bottom-0 w-full justify-center opacity-60 mb-2">
                        <CarouselIndicator v-for="data in modalData" :class="data.show?'bg-white':'bg-transparent'"/>
                    </div>
                    <button @click="next()" class="nextbtncontainer right-0 mr-1 absolute top-[50%]"><ArrowRight class="nextbtn rounded-full bg-cbc-dark-green h-6 w-auto active:scale-90 duration-200 text-white" /></button>
                </div>
            </div>
        </div>
    </transition>
</template>