<script>
import {Link} from "@inertiajs/vue3";

export default {
    name: "InfoPageLinks",
    components: {Link},
    data() {
        return {
            links: [
                {name: 'What is PIN?', route: 'support.what-is-pin'},
                {name: 'Terms of Use', route: 'support.terms-of-use'},
                /*{name: 'Policy Notice', route: 'support.policy-notice'},*/
                {name: 'Privacy Policy', route: 'support.privacy-policy'},
                {name: 'Data Privacy Notice', route: 'support.data-privacy'},
                {name: 'Contributors', route: 'support.contributors'},
                {name: 'Sitemap', route: 'support.sitemap'},
            ]
        }
    }
}
</script>

<template>
    <div class="flex flex-col gap-1">
        <Link v-for="link in links" :key="link.name" :href="route(link.route)"
              :class="['info-page-link group', { active: route().current(link.route) }]">
            <span class="w-1 h-5 rounded-full transition-colors" :class="route().current(link.route) ? 'bg-pin-green' : 'bg-transparent group-hover:bg-pin-green/30'"></span>
            <span>{{ link.name }}</span>
        </Link>
    </div>
</template>

<style scoped>
.info-page-link {
    @apply flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-gray-600 whitespace-nowrap transition-all duration-200;
}
.info-page-link:hover {
    @apply bg-pin-green-light text-pin-green;
}
.info-page-link.active {
    @apply bg-pin-green-light text-pin-green font-semibold;
}
</style>
