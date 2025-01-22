<script>
import {Head} from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import Map from "@/Pages/Projects/BreedersMap/presentation/components/map/Map.vue";
import CommodityTable from "@/Pages/Projects/BreedersMap/presentation/components/commodity/CommodityTable.vue";
import CrcmThead from "../../../../Components/CRCMDatatable/Components/CrcmThead.vue";
import TbodyRow from "../../../../Components/CRCMDatatable/Components/TbodyRow.vue";
import CrcmTable from "../../../../Components/CRCMDatatable/Components/CrcmTable.vue";
import TD from "../../../../Components/CRCMDatatable/Components/TD.vue";
import TH from "../../../../Components/CRCMDatatable/Components/TH.vue";
import CrcmTbody from "../../../../Components/CRCMDatatable/Components/CrcmTbody.vue";
import TheadRow from "../../../../Components/CRCMDatatable/Components/TheadRow.vue";
import Commodity from "@/Pages/Projects/BreedersMap/domain/Commodity";
import Characteristics from "@/Pages/Projects/BreedersMap/domain/Characteristics";
import AdditionalInfo from "@/Pages/Projects/BreedersMap/domain/AdditionalInfo";

export default {
    computed: {
        AdditionalInfo() {
            return AdditionalInfo
        },
        Characteristics() {
            return Characteristics
        },
        Commodity() {
            return Commodity
        },
        visibleColumns() {
            return this.Commodity.getColumns().filter(column => column.visible);
        },
        commodityInstance() {
            return new Commodity(this.commodity);
        }
    },
    components: {
        TheadRow,
        CrcmTbody, TH, TD, CrcmTable, TbodyRow, CrcmThead, Head, CommodityTable, AppLayout, Map},
    props: {
        commodity: {
            type: Object,
            required: false,
            default: null,
        }
    },
    methods: {
        getNestedValue(obj, path) {
            return path.split('.').reduce((acc, part) => acc && acc[part], obj);
        },
    }
}
</script>

<template>
    <Head title="Plant Breeders Map" />
    <app-layout>
    <div class="p-5">
        <crcm-table>
            <crcm-tbody>
                <!-- Commodity Table -->
                <thead-row>
                    <t-h column="Basic Information" colspan="2" />
                </thead-row>
                <thead-row v-for="column in Commodity.getColumns()" v-show="column.visible" :key="column.key + column.title">
                    <t-d class="px-3 border bg-gray-300" :column="column.title">{{ column.title }}</t-d>
                    <t-d class="px-3 border">
                        {{ getNestedValue(commodityInstance, column.key) }}
                    </t-d>
                </thead-row>
                <!-- Characteristics Table -->
                <thead-row>
                    <t-h column="Characteristics" colspan="2"/>
                </thead-row>
                <thead-row v-for="column in Characteristics.getColumns()" v-show="column.visible" :key="column.key + column.title">
                    <t-d class="px-3 border bg-gray-300" :column="column.title">{{ column.title }}</t-d>
                    <t-d class="px-3 border">
                        {{ getNestedValue(commodityInstance.characteristics, column.key) }}
                    </t-d>
                </thead-row>
                <!-- Additional Info Table -->
                <thead-row>
                    <t-h column="Additional Info" colspan="2"/>
                </thead-row>
                <thead-row v-for="column in AdditionalInfo.getColumns()" v-show="column.visible" :key="column.key + column.title">
                    <t-d class="px-3 border bg-gray-300" :column="column.title">{{ column.title }}</t-d>
                    <t-d class="px-3 border">
                        {{ getNestedValue(commodityInstance.additionalinfo, column.key) }}
                    </t-d>
                </thead-row>
            </crcm-tbody>
        </crcm-table>

        <!--                <h1 class="h1 text-center font-semibold uppercase select-none">Commodity Geo Location</h1>
        <Map v-if="commodity" :customPoint="commodity" />-->
    </div>
    </app-layout>

</template>

<style scoped>
#map {
    @apply sm:w-[50%] w-full h-screen z-0;
}
</style>
