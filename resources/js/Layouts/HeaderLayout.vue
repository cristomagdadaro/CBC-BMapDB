<script>
import { Link } from "@inertiajs/vue3";
import hamburger from "@/Components/Icons/Hamburger.vue";

export default {
    components: {
        Link,
        hamburger,
    },
    props: {
        active: Boolean,
    },
    data() {
        return {
            showMenu: false,
            isScrolled: false,
        };
    },
    mounted() {
        this.handleScroll();
        window.addEventListener('scroll', this.handleScroll, { passive: true });
    },
    beforeUnmount() {
        window.removeEventListener('scroll', this.handleScroll);
    },
    methods: {
        toggler() {
            this.showMenu = !this.showMenu;
        },
        handleScroll() {
            this.isScrolled = window.scrollY > 50;
        },
    },
};
</script>
<template>
    <header
        :class="[
            'fixed top-0 left-0 right-0 z-50 transition-all duration-500',
            isScrolled
                ? 'bg-white/95 backdrop-blur-xl shadow-lg py-2'
                : 'bg-transparent py-4'
        ]"
    >
        <nav class="section-padding">
            <div class="container-custom flex items-center justify-between">
                <!-- Logo / Branding -->
                <Link
                    :href="'/'"
                    class="flex items-center gap-3 group focus-ring rounded-lg"
                >
                    <slot name="icon"></slot>
                    <div class="hidden sm:block leading-tight">
                        <p :class="['text-xs font-medium transition-colors uppercase font-display', isScrolled ? 'text-gray-500' : 'text-white/80']">
                            <slot name="subtitle"></slot>
                        </p>
                        <p :class="['text-lg font-bold font-display transition-colors', isScrolled ? 'text-pin-green' : 'text-white']">
                            <slot name="title"></slot>
                        </p>
                    </div>
                </Link>

                <!-- Desktop Navigation -->
                <div class="hidden lg:flex items-center gap-1">
                    <slot name="links" :is-scrolled="isScrolled"></slot>
                </div>

                <!-- Mobile Menu Button -->
                <button
                    class="lg:hidden p-2 rounded-lg transition-colors focus-ring"
                    :class="isScrolled ? 'text-gray-700 hover:bg-gray-100' : 'text-white hover:bg-white/10'"
                    @click="toggler()"
                    :aria-expanded="showMenu"
                    :aria-label="showMenu ? 'Close menu' : 'Open menu'"
                >
                    <svg v-if="!showMenu" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    <svg v-else class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </nav>

        <!-- Mobile Menu -->
        <transition
            enter-active-class="transition ease-out duration-300"
            enter-from-class="opacity-0 -translate-y-2"
            enter-to-class="opacity-100 translate-y-0"
            leave-active-class="transition ease-in duration-200"
            leave-from-class="opacity-100 translate-y-0"
            leave-to-class="opacity-0 -translate-y-2"
        >
            <div
                v-show="showMenu"
                class="lg:hidden bg-white border-t border-gray-100 shadow-lg"
            >
                <div class="section-padding py-4 space-y-1">
                    <slot name="mobile-links"></slot>
                </div>
            </div>
        </transition>
    </header>
</template>
