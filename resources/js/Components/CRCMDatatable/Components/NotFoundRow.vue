<template>
    <tr>
        <td :colspan="props.colspan+1" class="text-center">
            <div class="flex items-center justify-center gap-1">
                <exclamation-circle-icon class="w-5 h-auto" />
                <span>No data found</span>
            </div>
        </td>
    </tr>
</template>
<script setup>
import {defineProps} from "vue";
import ExclamationCircleIcon from "@/Components/Icons/ExclamationCircleIcon.vue";
const props = defineProps({
    colspan: Number,
});
</script>
