<script>
import CloseIcon from '@/Components/Icons/Close.vue';

export default{
    components: {
        CloseIcon,
    },
    data() {
        return {
            showModal: true,
        };
    },
    methods: {
        close() {
            this.showModal = false;
        },
    },
}
</script>
<template>
    <div :class="showModal?'flex':'hidden'" class="min-h-screen min-w-full bg-gray-900 justify-center items-center z-50">
        <div class="relative border-4 border-cbc-dark-green max-h-[600px] min-h-[300px] min-w-[600px] max-w-[1000px]">
            <button class="top-0 right-0 absolute m-1 hover:scale-110 hover:rotate-180 duration-300 z-10" @click="close()"><CloseIcon class="sm:h-5 h-3 w-auto"/></button>
            <div class="absolute w-full h-full flex items-center justify-center bg-[url('/img/sample.jpg') bg-cover]">
                <img src="/img/sample.jpg" alt="Crop Biotechnology Center Logo" class="relative h-auto w-auto">
            </div>
            <a href="/" class="bottom-1 right-1 absolute m-1 hover:bg-cbc-yellow-green duration-300 bg-cbc-dark-green px-2 rounded-md text-xl text-[#fff] z-10">Read More</a>
        </div>
    </div>
</template>