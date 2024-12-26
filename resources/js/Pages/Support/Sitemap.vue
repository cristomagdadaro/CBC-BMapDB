<script>
import InfoPageLayout from "@/Pages/Support/components/InfoPageLayout.vue";
import {Link} from "@inertiajs/vue3";
import UnderDevelop from "@/Components/Modal/UnderDevelop.vue";
import axios from 'axios';

export default {
    name: "Sitemap",
    components: {UnderDevelop, Link, InfoPageLayout},
    data() {
        return {
            routes: this.$router.options.routes,
        }
    },
    computed: {
        sitemap() {
            const header = `<?xml version="1.0" encoding="UTF-8"?>\n<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">`;
            const footer = `</urlset>`;
            const body = this.$page.props.urls
                .map(
                    (url) =>
                        `  <url>\n` +
                        `    <loc>${url.loc}</loc>\n` +
                        `    <lastmod>${url.lastmod}</lastmod>\n` +
                        `    <changefreq>${url.changefreq}</changefreq>\n` +
                        `    <priority>${url.priority}</priority>\n` +
                        `  </url>`
                )
                .join("\n");
            return `${header}\n${body}\n${footer}`;
        },
    },
}
</script>

<template>
    <info-page-layout title="Sitemap">
        <div class="flex flex-col space-y-2">
            <span v-for="routes in $page.props.urls">
                <Link :href="routes.loc" class="text-gray-800 text-normal">{{ routes.name }}</Link>
            </span>
        </div>
        <textarea v-model="sitemap" readonly rows="15" cols="80" class="hidden"></textarea>
    </info-page-layout>
</template>

<style scoped>
.info-page-link {
    @apply text-gray-100 whitespace-nowrap;
}
</style>
