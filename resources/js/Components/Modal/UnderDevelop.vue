<template>
    <div class="min-h-screen flex items-center justify-center bg-transparent overflow-hidden sm:rounded-lg p-1 select-none">
        <div class="flex items-center justify-center shadow-md p-5 bg-gray-200">
            <cone-icon class="h-auto w-10 animate-bounce" />
            <h1 class="text-2xl">Currently under development.</h1>
        </div>
    </div>
</template>
<script>
import ConeIcon from "@/Components/Icons/ConeIcon.vue";
export default {
    name: "UnderDevelop",
    components: {
        ConeIcon,
    },
};
</script>
