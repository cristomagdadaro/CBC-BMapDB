<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import UnderDevelop from "@/Components/Modal/UnderDevelop.vue";
</script>

<template>
    <AppLayout title="Dashboard">
        <pre>
            {{ $page.props }}
        </pre>
        <under-develop />
    </AppLayout>
</template>
