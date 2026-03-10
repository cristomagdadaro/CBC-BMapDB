<script>

import ArrowRight from "@/Components/Icons/ArrowRight.vue";
import ArrowLeft from "@/Components/Icons/ArrowLeft.vue";

export default {
    components: {ArrowLeft, ArrowRight},
    data() {
        return {
            show: false,
        }
    },
    methods: {
        scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: "smooth"
            });
        }
    },
    mounted() {
        window.addEventListener('scroll', () => {
            this.show = window.scrollY > 100;
        });
    }
}

</script>

<template>
    <div v-show="show" class="fixed bottom-6 right-24 sm:right-28 z-40">
        <button @click="scrollToTop" class="bg-pin-green border border-pin-green-dark font-bold text-white rounded-full p-2 shadow-lg hover:bg-pin-green-dark transition-colors duration-300 focus-ring">
            <arrow-left class="w-6 h-6 rotate-90" />
        </button>
    </div>
</template>
