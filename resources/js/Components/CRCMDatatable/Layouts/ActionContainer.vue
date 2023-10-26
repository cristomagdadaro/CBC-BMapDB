<template>
    <div class="flex items-center justify-center">
        <div id="dtActionContainer" class="flex-wrap flex sm:justify-center justify-between gap-1">
            <slot />
        </div>
    </div>
</template>
<script>
import ThreeDotIcon from "@/Components/Icons/ThreeDotIcon.vue";

export default {
    components: {ThreeDotIcon},
}
</script>
