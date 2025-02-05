<script setup>

import {computed, ref} from "vue";
import {usePage} from "@inertiajs/vue3";
const page = usePage();
let messageList = page.props.messages;
let newMessage = ref('');
const messages = computed(() => [messageList, newMessage]);
</script>

<template>
    <div class="flex gap-2 items-center relative lg:w-1/2 w-full mx-auto">
        <chat-messages :messages="messages" />
        <chat-form :user="page.props.auth.user.id" @messageSent="newMessage = $event" />
    </div>
</template>

<style scoped>

</style>
