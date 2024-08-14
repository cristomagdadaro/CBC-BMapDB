<script>
import { ref } from "vue";
import Hamburger from "@/Components/Icons/Hamburger.vue";

export default {
    components: {
        Hamburger,
    },
    computed: {
        isOpen() {
            return this.$store.state.isSidebarOpen;
        },
    },
    methods: {
        toggleSidebar() {
            this.$store.dispatch("asyncToggleSidebar");
        },
    },
};
</script>

<template>
    <div class="flex z-50">
        <div
            :class="isOpen ? 'w-44' : 'w-12'"
            class="sm:flex flex-col p-3 duration-300 bg-gray-400 shadow hidden">
            <div class="flex items-center text-gray-100 justify-end">
                <button @click="toggleSidebar">
                    <hamburger />
                </button>
            </div>
            <div class="flex flex-col" :class="isOpen ? 'block' : 'hidden'">
                <slot name="options" />
            </div>
        </div>
        <div class="w-full min-h-screen">
            <slot name="content" />
        </div>
    </div>
</template>

<style scoped>

</style>
