<template>
    <footer class="bottom-0 w-full min-h-fit select-none bg-cbc-footer">
        <div class="relative flex flex-col">
            <div class="resp-container">
                <slot name="phRepublic" />
            </div>
            <div class="resp-container">
                <slot name="contactUs" />
            </div>
            <div class="w-full">
                <slot name="privacyNotice" />
            </div>
        </div>
    </footer>
</template>
<script setup>
</script>
