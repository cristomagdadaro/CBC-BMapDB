<script>
import InfoPageLinks from "@/Pages/Support/components/InfoPageLinks.vue";
import {Head} from "@inertiajs/vue3";
import PageLayout from "@/Layouts/PageLayout.vue";

export default {
    name: "InfoPageLayout",
    components: {PageLayout, Head, InfoPageLinks},
    props: {
        title: {
            type: String,
            required: true
        },

    }
}
</script>

<template>
    <Head :title="title" />
    <page-layout>
        <div class="min-h-screen bg-pin-gray">
            <div class="section-padding py-12 lg:py-20">
                <div class="container-custom">
                    <div class="flex flex-col lg:flex-row gap-8">
                        <!-- Sidebar -->
                        <aside class="lg:w-64 flex-shrink-0">
                            <div class="lg:sticky lg:top-24">
                                <nav class="bg-white rounded-xl shadow-sm p-4">
                                    <h3 class="text-xs font-semibold text-gray-400 uppercase tracking-wider px-3 mb-3">Support</h3>
                                    <info-page-links />
                                </nav>
                            </div>
                        </aside>
                        <!-- Content -->
                        <main class="flex-1 min-w-0">
                            <div class="bg-white rounded-xl shadow-sm p-6 lg:p-10">
                                <h1 class="text-2xl lg:text-3xl font-bold text-gray-900 font-display mb-6 pb-4 border-b border-gray-100">
                                    {{ title }}
                                </h1>
                                <div class="prose prose-gray max-w-none text-gray-600 leading-relaxed">
                                    <slot />
                                </div>
                            </div>
                        </main>
                    </div>
                </div>
            </div>
        </div>
    </page-layout>
</template>
