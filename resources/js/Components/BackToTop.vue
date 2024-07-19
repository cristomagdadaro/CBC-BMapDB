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
    <div v-show="show" class="fixed bottom-0 right-0 mb-4 mr-4 z-[999]">
        <button @click="scrollToTop" class="bg-cbc-dark-green border font-bold text-white rounded-full p-2 shadow hover:bg-gray-600">
            <arrow-left class="w-6 h-6 rotate-90" />
        </button>
    </div>
</template>
