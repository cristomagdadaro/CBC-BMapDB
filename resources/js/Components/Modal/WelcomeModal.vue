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
    },
    props: {
        showModal: {
            type: Boolean,
            default: true,
        },
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
            ]
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
}
</script>
<template>
    <div :class="showModal?'flex':'hidden'" class="bgoverlay">
        <div class="modalcontainer">
            <button class="closemodal top-0 right-0 absolute m-1 hover:scale-110 hover:rotate-180 duration-300 z-10" @click="close()" title="Close modal"><CloseIcon class="sm:h-5 h-3 w-auto rounded-md bg-cbc-dark-green"/></button>
            <div class="flex flex-row relative">
                <button @click="prev()" class="prevbtnccontainer ml-1"><ArrowLeft class="prevbtn" /></button>
                <div class="flex-row flex">
                    <template v-for="data in modalData">
                        <img :src="data.image" :alt="data.alternative" class="object-cover" :class="data.show?'block':'hidden'">
                        <a :href="data.url" :class="data.url?'block':'hidden'" class="readmorebtn">Read More</a>
                    </template>
                </div>
                <div class="absolute flex gap-2 bottom-0 w-full justify-center opacity-40 mb-2">
                    <CarouselIndicator />
                </div>
                <button @click="next()" class="nextbtncontainer right-0 mr-1"><ArrowRight class="nextbtn" /></button>
            </div>
        </div>
    </div>
</template>
<style scoped>
    .nextbtncontainer, .prevbtnccontainer{
        @apply absolute top-[50%];
    }
    
    .nextbtn, .prevbtn, .closemodal{
        @apply h-6 w-auto active:scale-90 duration-200 text-white;
    }

    .nextbtn, .prevbtn{
        @apply rounded-full bg-cbc-dark-green;
    }

    .bgoverlay{
        @apply absolute min-h-screen min-w-full justify-center items-center z-50 backdrop-blur-sm backdrop-brightness-75;
    }

    .modalcontainer{
        @apply relative border-0 border-white max-h-[90%] max-w-[90%] sm:max-h-[50%] sm:max-w-[50%] aspect-video;
    }

    .readmorebtn{
        @apply bottom-1 right-1 absolute m-1 hover:bg-cbc-yellow-green duration-300 bg-cbc-dark-green px-2 rounded-md text-xl text-[#fff] z-10;
    }
</style>