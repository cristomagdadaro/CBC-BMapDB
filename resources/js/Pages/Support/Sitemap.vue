<script>
import InfoPageLayout from "@/Pages/Support/components/InfoPageLayout.vue";
import {Link} from "@inertiajs/vue3";
import UnderDevelop from "@/Components/Modal/UnderDevelop.vue";

export default {
    name: "Sitemap",
    components: {UnderDevelop, Link, InfoPageLayout},
    data() {
        return {
            routes: this.$router.options.routes
        }
    }
}
</script>

<template>
    <info-page-layout title="Sitemap">
        <under-develop />
    </info-page-layout>
</template>

<style scoped>
.info-page-link {
    @apply text-gray-100 whitespace-nowrap;
}
</style>
