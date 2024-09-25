<script>
import InfoPageLayout from "@/Pages/Support/components/InfoPageLayout.vue";
import UnderDevelop from "@/Components/Modal/UnderDevelop.vue";

export default {
    name: "Developers",
    components: {UnderDevelop, InfoPageLayout}
}
</script>

<template>
    <info-page-layout title="Developers">
        <under-develop />
    </info-page-layout>
</template>

<style scoped>

</style>
