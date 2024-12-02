<script>
import {Link} from "@inertiajs/vue3";

export default {
    name: "InfoPageLinks",
    components: {Link},
    data() {
        return {
            links: [
                {name: 'About Us', route: 'support.about-us'},
                {name: 'Terms of Use', route: 'support.terms-of-use'},
                {name: 'Policy Notice', route: 'support.policy-notice'},
                {name: 'Privacy Policy', route: 'support.privacy-policy'},
                {name: 'Sitemap', route: 'support.sitemap'},
                {name: 'Developers', route: 'support.developers'},
            ]
        }
    }
}
</script>

<template>
    <Link v-for="link in links" :key="link.name" :href="route(link.route)" :class="{ active: route().current(link.route) }" class="info-page-link sm:text-sm text-[0.8rem]">{{ link.name }}</Link>
</template>

<style scoped>
.info-page-link {
    @apply text-gray-100 whitespace-nowrap;
}
.info-page-link:hover {
    @apply text-cbc-olive-green;
}

.info-page-link.active {
    @apply text-cbc-olive-green;
}
</style>
