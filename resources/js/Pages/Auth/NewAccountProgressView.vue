<script setup>

import CheckallIcon from "@/Components/Icons/CheckallIcon.vue";

const register = route().current('register') && !route().current('verification.notice') && !route().current('admin.approval');
const verify = route().current('verification.notice') && !route().current('dashboard');
const approval = route().current('dashboard');

</script>

<template>
    <div class="mb-6 text-xs sm:text-sm items-center flex gap-2 justify-between overflow-x-auto pb-1">
        <!-- Step 1: Registration -->
        <div
            :class="[
                verify || approval
                    ? 'bg-emerald-700 text-white shadow-sm'
                    : register
                        ? 'bg-pin-green text-white font-semibold shadow-sm ring-2 ring-pin-green/30'
                        : 'bg-gray-100 text-gray-700 border border-gray-200'
            ]"
            class="items-center whitespace-nowrap flex gap-1.5 rounded-full sm:px-4 sm:py-2 px-3 py-1.5 transition-colors"
        >
            <span class="flex items-center gap-1">
                <span class="font-semibold">Step 1:</span>
                <span class="hidden sm:inline">Registration</span>
            </span>
            <checkall-icon v-if="verify || approval" class="w-4 h-4 text-emerald-100" />
        </div>

        <!-- Step 2: Email Verification -->
        <div
            :class="[
                approval
                    ? 'bg-emerald-700 text-white shadow-sm'
                    : verify
                        ? 'bg-pin-green text-white font-semibold shadow-sm ring-2 ring-pin-green/30 animate-pulse'
                        : 'bg-gray-100 text-gray-700 border border-gray-200'
            ]"
            class="items-center whitespace-nowrap flex gap-1.5 rounded-full sm:px-4 sm:py-2 px-3 py-1.5 transition-colors"
        >
            <span class="flex items-center gap-1">
                <span class="font-semibold">Step 2:</span>
                <span class="hidden sm:inline">Email Verification</span>
            </span>
            <checkall-icon v-if="approval" class="w-4 h-4 text-emerald-100" />
        </div>

        <!-- Step 3: Admin Approval -->
        <div
            :class="[
                approval
                    ? 'bg-pin-green text-white font-semibold shadow-sm ring-2 ring-pin-green/30 animate-pulse'
                    : 'bg-gray-100 text-gray-700 border border-gray-200'
            ]"
            class="items-center whitespace-nowrap flex gap-1.5 rounded-full sm:px-4 sm:py-2 px-3 py-1.5 transition-colors"
        >
            <span class="flex items-center gap-1">
                <span class="font-semibold">Step 3:</span>
                <span class="hidden sm:inline">Admin Approval</span>
            </span>
        </div>
    </div>
</template>

<style scoped>

</style>
