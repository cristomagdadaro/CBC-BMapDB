<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import UnderDevelop from "@/Components/Modal/UnderDevelop.vue";
import User from "@/Modules/core/domain/auth/User.ts";
</script>

<template>
    <AppLayout title="Dashboard">
        <pre>
            {{ new User($page.props.auth.user) }}
        </pre>
        <under-develop />
    </AppLayout>
</template>
