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
        <div class="flex flex-col gap-2">
            <Link v-for="routes in $page.props.urls" :key="routes.loc" :href="routes.loc"
                  class="flex items-center justify-between p-3 rounded-lg hover:bg-pin-green-light transition-colors group">
                <span class="text-gray-700 group-hover:text-pin-green transition-colors">{{ routes.name }}</span>
                <svg class="w-4 h-4 text-gray-400 group-hover:text-pin-green transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </Link>
        </div>
        <textarea v-model="sitemap" readonly rows="15" cols="80" class="hidden"></textarea>
    </info-page-layout>
</template>

<style scoped>
</style>
