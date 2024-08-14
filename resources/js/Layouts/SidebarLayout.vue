<script>
import Hamburger from "@/Components/Icons/Hamburger.vue";
import BreadCrumb from "@/Components/BreadCrumb.vue";

export default {
    components: {
        BreadCrumb,
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
            :class="isOpen ? 'w-64' : 'w-12'"
            class="sm:flex flex-col p-3 duration-300 bg-[#BBC3A4] shadow hidden">
            <div class="flex items-center text-gray-100 justify-end">
                <button @click="toggleSidebar" class="hover:scale-110 hover:drop-shadow duration-100">
                    <hamburger />
                </button>
            </div>
            <div class="flex flex-col" :class="isOpen ? 'block' : 'hidden'">
                <slot name="options" />
            </div>
        </div>
        <div class="w-full min-h-screen sm:p-3 p-0">
            <BreadCrumb />
            <slot name="content" />
        </div>
    </div>
</template>

<style scoped>

</style>
