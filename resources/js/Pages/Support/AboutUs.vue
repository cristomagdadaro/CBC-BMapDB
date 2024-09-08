<script>
import {Head, Link} from "@inertiajs/vue3";
import PageLayout from "@/Layouts/PageLayout.vue";
import InfoPageLinks from "@/Pages/Support/components/InfoPageLinks.vue";
import InfoPageLayout from "@/Pages/Support/components/InfoPageLayout.vue";

export default {
    name: "AboutUs",
    components: {InfoPageLayout, InfoPageLinks, PageLayout, Head, Link}
}
</script>

<template>
    <info-page-layout title="About Us">
        <div class="space-y-6 text-gray-800 leading-relaxed">
            <p class="text-lg">
                The Crop Biotechnology Center (CBC) is a research and development facility of the Department of Agriculture (DA) that is dedicated to the advancement of crop biotechnology in the Philippines. Established in 2000, the CBC is committed to the development and promotion of biotechnology applications in agriculture to enhance food security, increase crop productivity, and improve the livelihoods of Filipino farmers.
            </p>
            <p class="text-lg">
                The CBC is a key player in the Philippine government’s efforts to harness the potential of biotechnology to address the challenges of food security, poverty alleviation, and sustainable development. Through its research and development programs, the CBC aims to develop and deploy biotechnology tools and technologies that will help farmers produce more food with less resources, reduce the environmental impact of agriculture, and improve the nutritional quality of crops.
            </p>
            <p class="text-lg">
                The CBC is also actively involved in capacity building, technology transfer, and public awareness activities to promote the responsible use of biotechnology in agriculture. The CBC works closely with government agencies, academic institutions, research organizations, and industry partners to ensure that the benefits of biotechnology are shared equitably and sustainably.
            </p>
            <p class="text-lg">
                The CBC is committed to the principles of transparency, accountability, and stakeholder engagement in all its activities. The CBC is guided by the principles of good governance, ethical conduct, and social responsibility in its research and development programs. The CBC is committed to promoting the safe and responsible use of biotechnology in agriculture to benefit the Filipino people and the environment.
            </p>
            <p class="text-lg">
                The CBC is proud to be a part of the Department of Agriculture and the Philippine government’s efforts to promote sustainable agriculture, food security, and rural development. The CBC is dedicated to the advancement of crop biotechnology in the Philippines and looks forward to contributing to the country’s agricultural development and economic growth.
            </p>
        </div>

    </info-page-layout>
</template>

<style scoped>

</style>
