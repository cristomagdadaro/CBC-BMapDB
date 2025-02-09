<script setup>

import CheckallIcon from "@/Components/Icons/CheckallIcon.vue";

const register = route().current('register') && !route().current('verification.notice') && !route().current('admin.approval');
const verify = route().current('verification.notice') && !route().current('dashboard');
const approval = route().current('dashboard');

</script>

<template>
    <div class="select-none mb-5 text-sm items-center flex gap-1 drop-shadow-md justify-between overflow-x-hidden">
        <div :class="{'animate-pulse': register }"
             class="items-center shadow-2xl whitespace-nowrap flex gap-1 rounded-full bg-cbc-dark-green text-white sm:p-3 px-3 py-1">
            <span class="flex">Step 1: <span class="hidden md:block">Registration</span></span>
            <checkall-icon v-if="verify || approval" class="w-6 h-6" />
        </div>
        <div :class="{'animate-pulse': verify, 'opacity-25': register}"
             class="items-center shadow-2xl whitespace-nowrap flex gap-1 rounded-full bg-cbc-dark-green text-white sm:p-3 px-3 py-1">
            <span class="flex">Step 2: <span class="hidden md:block">Email Verification</span></span>
            <checkall-icon v-if="approval" class="w-6 h-6" />
        </div>
        <div :class="{'animate-pulse': approval, 'opacity-25': verify || register}"
             class="items-center whitespace-nowrap flex gap-1 shadow-2xl rounded-full bg-cbc-dark-green text-white sm:p-3 px-3 py-1">
            <span class="flex">Step 3: <span class="hidden md:block">Admin Approval</span></span>
        </div>
    </div>
</template>

<style scoped>

</style>
