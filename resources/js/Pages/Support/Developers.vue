<script>
import InfoPageLayout from "@/Pages/Support/components/InfoPageLayout.vue";
import UnderDevelop from "@/Components/Modal/UnderDevelop.vue";
import {usePage} from "@inertiajs/vue3";

export default {
    name: "Developers",
    components: {UnderDevelop, InfoPageLayout},
    data() {
        return {
            page: usePage(),
        }
    },
    computed: {
        appName(){
            return this.page.props.appName;
        },
        appNameShort(){
            return this.page.props.appNameShort;
        },
        companyName(){
            return this.page.props.companyName;
        },
        companyNameShort(){
            return this.page.props.companyNameShort;
        },
    }
}
</script>

<template>
    <info-page-layout title="Contributors">
        <div class="space-y-6 text-gray-800 leading-5">
            <p class="text-lg">
                The {{ appNameShort}} serves as a centralized repository for crop biotechnology-related data, studies, and research. We welcome the Department of Agriculture (DA), its regional offices (DA-RFO), Biotechnology Program Office (DA-BPO), DA Research Institutions, and State Universities and Colleges (SUCs) to join us in this collaborative effort.
            </p>
            <under-develop />
        </div>
    </info-page-layout>
</template>

<style scoped>

</style>
