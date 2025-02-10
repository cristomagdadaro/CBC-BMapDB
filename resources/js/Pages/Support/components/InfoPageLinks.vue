<script>
import {Link} from "@inertiajs/vue3";

export default {
    name: "InfoPageLinks",
    components: {Link},
    data() {
        return {
            links: [
                {name: 'What is PIN?', route: 'support.what-is-pin'},
                {name: 'Virtual Tour', route: 'support.cbc-tour'},
                {name: 'Terms of Use', route: 'support.terms-of-use'},
                /*{name: 'Policy Notice', route: 'support.policy-notice'},*/
                {name: 'Privacy Policy', route: 'support.privacy-policy'},
                {name: 'Contributors', route: 'support.developers'},
                {name: 'Sitemap', route: 'support.sitemap'},
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
