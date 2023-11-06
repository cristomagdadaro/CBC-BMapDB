<script setup>
defineProps({
    message: String,
});
</script>

<template>
    <div v-show="message">
        <p class="text-xs text-red-600">
            <template v-for="msg in message">
                <span v-if="msg" v-text="msg"></span>
            </template>
        </p>
    </div>
</template>
