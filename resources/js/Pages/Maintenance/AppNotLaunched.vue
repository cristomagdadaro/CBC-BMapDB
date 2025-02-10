<template>
    <Head title="System Maintenance" />

    <div id="maintenance-particles-js" class="absolute top-0 left-0 w-full h-full -z-[999]"></div>
    <div class="flex items-center justify-center h-screen">
        <authentication-card>
            <div class="flex flex-col items-center justify-center text-center">
                <h1 class="text-subtitle">Available Soon</h1>
                <p class="text-normal">We know you're excited! Something amazing is on the way</p>
                <p>Stay tuned—we'll be launching soon</p>
                <div class="flex gap-1 drop-shadow-lg">
                    <authentication-card-logo />
                    <img src="/img/logo-black.png" class="w-[5rem] sm:w-[6rem] md:w-[7rem] lg:w-[8rem] h-auto" alt="DA-CBC Logo">
                </div>
            </div>
        </authentication-card>
    </div>
</template>

<script>
import {Head} from "@inertiajs/vue3";
import AuthenticationCard from "@/Components/AuthenticationCard.vue";
import AuthenticationCardLogo from "@/Components/AuthenticationCardLogo.vue";
export default {
    components: {
        Head, AuthenticationCard, AuthenticationCardLogo,
    },
    methods: {
        setupParticlesFor(id)  {
            particlesJS.load(id, '/particlesjs-config.json', function() {
                console.log('particles for Projects Page loaded');
            });
        },
    },
    mounted() {
        this.setupParticlesFor('maintenance-particles-js');
    },
}
</script>
