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
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
                    <div class="text-2xl">
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
