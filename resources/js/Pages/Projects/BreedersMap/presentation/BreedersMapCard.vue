<script>

import {defineComponent} from "vue";
import DashboardCard from "@/Components/DashboardCard.vue";

export default defineComponent({
    components: {DashboardCard}
})
</script>

<template>
    <dashboard-card class="bg-cbc-brown text-white">
        <template v-slot:title>
            <div class="flex flex-col leading-[1]">
                <span>
                    Breeders Map Database
                </span>
                <span class="text-[0.7rem] text-gray-400">
                    Recent Updates
                </span>
            </div>
        </template>
        <template v-slot:body>
            <div class="flex flex-col gap-1">
                <div class="text-cbc-brown bg-gray-100 px-3 py-1 rounded">
                    10 new breeders!
                </div>
                <div class="text-cbc-brown bg-gray-100 px-3 py-1 rounded">
                    4053 new varieties across 35 commodities
                </div>
                <div class="text-cbc-brown bg-gray-100 px-3 py-1 rounded">
                    6 new institutions joined our initiative
                </div>
                <div class="text-cbc-brown bg-gray-100 px-3 py-1 rounded">
                    Researchers can now request more information directly to the breeders
                </div>
            </div>
        </template>
    </dashboard-card>
</template>

<style scoped>

</style>
