<template>
    <footer class=" bottom-0 w-full min-h-fit">
        <div class="relative flex flex-col">
            <div class="bg-gray-600 text-white">
                <div class="resp-container">
                    <slot name="phRepublic" />
                </div>
            </div>
            <div class="w-full bg-gray-700">
                <slot name="copyright" />
            </div>
        </div>

    </footer>
</template>
<script setup>
</script>
