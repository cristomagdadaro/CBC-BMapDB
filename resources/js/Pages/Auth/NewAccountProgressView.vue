<script setup>

import CheckallIcon from "@/Components/Icons/CheckallIcon.vue";

const register = route().current('register') && !route().current('verification.notice') && !route().current('admin.approval');
const verify = route().current('verification.notice') && !route().current('dashboard');
const approval = route().current('dashboard');

</script>

<template>
    <div class="mb-5 text-sm items-center flex gap-2 justify-between overflow-x-auto">
        <div :class="{'animate-pulse': register }"
             class="items-center whitespace-nowrap flex gap-1.5 rounded-full bg-pin-green text-white sm:px-4 sm:py-2.5 px-3 py-1.5 shadow-sm">
            <span class="flex">Step 1: <span class="hidden md:block">Registration</span></span>
            <checkall-icon v-if="verify || approval" class="w-5 h-5" />
        </div>
        <div :class="{'animate-pulse': verify, 'opacity-25': register}"
             class="items-center whitespace-nowrap flex gap-1.5 rounded-full bg-pin-green text-white sm:px-4 sm:py-2.5 px-3 py-1.5 shadow-sm">
            <span class="flex">Step 2: <span class="hidden md:block">Email Verification</span></span>
            <checkall-icon v-if="approval" class="w-5 h-5" />
        </div>
        <div :class="{'animate-pulse': approval, 'opacity-25': verify || register}"
             class="items-center whitespace-nowrap flex gap-1.5 rounded-full bg-pin-green text-white sm:px-4 sm:py-2.5 px-3 py-1.5 shadow-sm">
            <span class="flex">Step 3: <span class="hidden md:block">Admin Approval</span></span>
        </div>
    </div>
</template>

<style scoped>

</style>
