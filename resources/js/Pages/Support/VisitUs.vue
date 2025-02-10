<script>
import InfoPageLayout from "@/Pages/Support/components/InfoPageLayout.vue";
import UnderDevelop from "@/Components/Modal/UnderDevelop.vue";

export default {
    name: "VisitUs",
    components: {UnderDevelop, InfoPageLayout},
}
</script>

<template>
    <info-page-layout title="Virtual 360 Tour">
        <p class="my-1 text-sm">You may visit the actual web app through <a href="https://cbc360tour.philrice.gov.ph/" target="_blank" class="underline font-bold">cbc360tour.philrice.gov.ph</a></p>
        <iframe
            src="https://cbc360tour.philrice.gov.ph/"
            style="border: none; width: 100%; height: 1000px; margin: 0 auto;"
            allowfullscreen
        ></iframe>
    </info-page-layout>
</template>

<style scoped>

</style>
