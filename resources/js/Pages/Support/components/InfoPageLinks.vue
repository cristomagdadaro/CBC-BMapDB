<script>
import {Link} from "@inertiajs/vue3";

export default {
    name: "InfoPageLinks",
    components: {Link},
}
</script>

<template>
    <Link :href="route('support.about-us')" :class="{ active: route().current('support.about-us') }" class="info-page-link">About Us</Link>
    <Link :href="route('support.terms-of-use')" :class="{ active: route().current('support.terms-of-use') }" class="info-page-link">Terms of Use</Link>
    <Link :href="route('support.policy-notice')" :class="{ active: route().current('support.policy-notice') }" class="info-page-link">Policy Notice</Link>
    <Link :href="route('support.privacy-policy')" :class="{ active: route().current('support.privacy-policy') }" class="info-page-link">Privacy Policy</Link>
    <Link :href="route('support.sitemap')" :class="{ active: route().current('support.sitemap') }" class="info-page-link">Sitemap</Link>
    <Link :href="route('support.developers')" :class="{ active: route().current('support.developers') }" class="info-page-link">Developers</Link>
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
