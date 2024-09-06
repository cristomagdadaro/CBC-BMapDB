<template>
    <footer class="bottom-0 w-full min-h-fit select-none bg-cbc-footer">
        <div class="relative flex flex-col">
            <div>
                <div class="resp-container">
                    <slot name="phRepublic" />
                </div>
            </div>
            <div class="w-full">
                <slot name="copyright" />
            </div>
        </div>
    </footer>
</template>
<script setup>
</script>
