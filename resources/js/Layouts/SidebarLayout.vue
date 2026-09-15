<template>
    <div
        class="flex z-30 min-h-screen bg-gray-50 dark:bg-gray-900 transition-colors duration-200"
    >
        <!-- Sidebar Navigation -->
        <aside
            :class="isOpen ? 'w-64' : 'w-16'"
            class="hidden sm:flex flex-col p-3 duration-300 ease-in-out bg-[#BBC3A4] dark:bg-gray-800 border-r border-transparent dark:border-gray-700/60 shadow-sm transition-all"
        >
            <!-- Toggle Header -->
            <div
                class="flex items-center mb-3"
                :class="isOpen ? 'justify-between px-1' : 'justify-center'"
            >
                <span
                    v-if="isOpen"
                    class="text-xs font-semibold uppercase tracking-wider text-gray-700 dark:text-gray-300 transition-opacity duration-200"
                >
                    Navigation
                </span>
                <button
                    @click="toggleSidebar"
                    aria-label="Toggle Navigation Sidebar"
                    class="p-1.5 rounded-lg text-gray-700 dark:text-gray-200 hover:bg-black/10 dark:hover:bg-gray-700/60 hover:scale-105 active:scale-95 transition-all duration-150 focus:outline-none focus:ring-2 focus:ring-emerald-500/50"
                >
                    <hamburger class="w-5 h-5" />
                </button>
            </div>

            <!-- Sidebar Slot Content -->
            <div
                class="flex flex-col space-y-1 overflow-y-auto overflow-x-hidden scrollbar-none"
                :class="isOpen ? 'block opacity-100' : 'hidden opacity-0'"
            >
                <slot name="options" />
            </div>
        </aside>

        <!-- Main Workspace Area -->
        <main
            class="flex-1 w-full min-h-screen p-0 flex flex-col bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 transition-colors duration-200"
        >
            <BreadCrumb class="dark:bg-gray-800/80 dark:border-gray-700/60" />

            <div class="flex-1">
                <slot name="content" />
            </div>
        </main>
    </div>
</template>

<script>
import Hamburger from "@/Components/Icons/Hamburger.vue";
import BreadCrumb from "@/Components/BreadCrumb.vue";

export default {
    name: "AppSidebar",
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

<style scoped>
/* Utility class to hide scrollbars while allowing scroll behavior */
.scrollbar-none::-webkit-scrollbar {
    display: none;
}
.scrollbar-none {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
</style>
