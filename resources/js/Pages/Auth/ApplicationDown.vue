<script>
import AppLayout from "@/Layouts/AppLayout.vue";
import { Head } from '@inertiajs/vue3';

export default {
    name: "ApplicationDown",
    components: { AppLayout, Head }
}
</script>

<template>
<app-layout>
    <Head title="Application Down" />
    <div class="py-12 bg-pin-gray min-h-[70vh]">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-200">
                <div class="p-8 sm:px-10 bg-white">
                    <div class="badge-primary mb-4 inline-flex">System Notice</div>
                    <div class="text-2xl font-bold text-gray-900 font-display leading-snug">
                        {{ $page.props.message }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</app-layout>
</template>

<style scoped>

</style>
