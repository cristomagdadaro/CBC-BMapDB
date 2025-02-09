<script>
import {Head, Link} from "@inertiajs/vue3";
import PageLayout from "@/Layouts/PageLayout.vue";
import InfoPageLinks from "@/Pages/Support/components/InfoPageLinks.vue";
import InfoPageLayout from "@/Pages/Support/components/InfoPageLayout.vue";

export default {
    name: "AboutUs",
    components: {InfoPageLayout, InfoPageLinks, PageLayout, Head, Link},
}
</script>

<template>
    <info-page-layout title="About Us">
        <div class="space-y-6 text-dark-color leading-5">
            <div>
                <p class="text-lg font-bold">What is  {{ $appNameShort }}?</p>
                <p>The  {{ $appName }}) System is an innovative online platform designed to serve as a centralized repository for crop biotechnology-related data. It aims to collect, store, and organize comprehensive information on various crops, including rice, corn, vegetables, and other agricultural products.</p>
            </div>
            <div>
                <p class="font-bold">The system's primary objectives are:</p>
                <ul class="list-decimal list-inside">
                    <li><span class="font-semibold">Data Collection:</span> Gather and manage vast amounts of data from diverse sources within the agricultural sector.</li>
                    <li><span class="font-semibold">Data Organization:</span> Store this information in a structured manner to facilitate easy access and retrieval.</li>
                    <li><span class="font-semibold">Collaboration:</span> Foster collaboration among researchers, breeders, and other stakeholders by providing a shared platform for knowledge exchange.</li>
                </ul>
            </div>
            <div>
                <p class="font-bold">Modularity and Future-Proofing</p>
                <p>The {{ $appNameShort }} is designed with modularity in mind, allowing it to easily integrate additional databases in the future. This feature ensures that the system remains scalable and adaptable as new data sources become available, such as coconut germplasm or other crops.</p>
            </div>

            <div>
                <p class="font-bold">The Team Driving Innovation</p>
                The development and oversight of the {{ $appNameShort }} are under the guidance of a dedicated team led by Dr. Roel R. Suralta, an esteemed expert in plant breeding with extensive experience in national agricultural programs. His team, the R&D Management Team at {{ $companyName }} ({{$companyNameShort}}), is committed to ensuring the system meets the highest standards of data integrity and innovation.
            </div>

            <div>
                <p class="font-bold">Broader Impact</p>
                <p>The {{ $appNameShort }} plays a pivotal role in supporting the Department of Agriculture's Biotechnology Program Office (DA-BPO) by providing critical tools for agricultural research and development. It contributes to the country's food security and sustainability goals by promoting efficient use of resources and fostering sustainable agricultural practices.</p>
                <p>Through {{ $appNameShort }}, we strive to empower researchers, breeders, and policymakers with cutting-edge tools that enable them to drive progress in crop biotechnology, ultimately benefiting the nation's agriculture sector.</p>
            </div>
        </div>
    </info-page-layout>
</template>

<style scoped>

</style>
