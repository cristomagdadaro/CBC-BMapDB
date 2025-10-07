<template>
    <Head title="Plant Breeders Map" />
    <app-layout>
        <Tab :tabs="tabs" v-if="$page.props.auth.user">
            <template v-slot:tab1>
                <summary-comp />
            </template>
            <template v-slot:tab2>
                <breeders-table />
            </template>
            <template v-slot:tab3>
                <commodity-table />
            </template>
            <template v-slot:tab4>
                <div class="flex flex-col gap-5 relative">

                    <MapExample />

<!--                    <Map :table-list="tables" :model="Commodity"/>-->
                </div>
            </template>
            <template v-slot:tab5>
                <div>
                    <under-develop />
                </div>
            </template>
            <template v-slot:tab6>
                <bm-settings />
            </template>
        </Tab>
        <p v-else>Please login to view the data</p>
    </app-layout>
</template>
<style scoped>
#map {
    @apply sm:w-[50%] w-full h-screen z-0;
}
</style>
<script>
import { Head } from "@inertiajs/vue3";
import { defineAsyncComponent } from "vue";
import Commodity from "@/Pages/Projects/BreedersMap/domain/Commodity";
import Breeder from "@/Pages/Projects/BreedersMap/domain/Breeder";
import BmSettings from "@/Pages/Projects/BreedersMap/presentation/components/misc/BmSettings.vue";
import UnderDevelop from "@/Components/Modal/UnderDevelop.vue";
import MapExample from "@/Components/Map/MapExample.vue";

export default {
    computed: {
        Commodity() {
            return Commodity
        }
    },
    components: {
        MapExample,
        UnderDevelop,
        BmSettings,
        Head,
        AppLayout: defineAsyncComponent({
            loader: async() => await import("@/Layouts/AppLayout.vue"),
        }),
        Tab: defineAsyncComponent({
            loader: async() => await import("@/Components/Tab/Tab.vue"),
        }),
        BreedersTable:  defineAsyncComponent({
            loader: async() => await import("@/Pages/Projects/BreedersMap/presentation/components/breeders/BreedersTable.vue"),
        }),
        CommodityTable: defineAsyncComponent({
            loader: async() => await import("@/Pages/Projects/BreedersMap/presentation/components/commodity/CommodityTable.vue"),
        }),
        Map: defineAsyncComponent({
            loader: async() => await import("@/Pages/Projects/BreedersMap/presentation/components/map/Map.vue"),
        }),
        SummaryComp: defineAsyncComponent({
            loader: async() => await import("@/Pages/Projects/BreedersMap/presentation/components/summary/Summary.vue"),
        })
    },
    data() {
      return {
          tabs: [
              {
                  name: "tab1",
                  label: "Summary",
                  active: true,
                  route: { name: 'projects.breedersmap.summary' },
              },
              {
                  name: "tab2",
                  label: "Breeders",
                  active: false,
                  route: { name: 'projects.breedersmap.breeder' },
              },
              {
                  name: "tab3",
                  label: "Commodities",
                  active: false,
                  route: { name: 'projects.breedersmap.commodity' },
              },
              {
                  name: "tab4",
                  label: "Geo Map",
                  active: false,
                  route: { name: 'projects.breedersmap.geomap' },
              },
              {
                  name: "tab5",
                  label: "Gene Bank",
                  active: true,
                  route: { name: 'projects.breedersmap.summary' },
              },
              {
                  name: "tab6",
                  label: "Settings",
                  active: false,
                  route: { name: 'projects.breedersmap.settings' },
              },
          ],
          tables: [
              { label: 'Commodity', name: 'commodities', route: route(Commodity.summaryUri), model: Commodity },
              { label: 'Breeders', name: 'breeders', route: route(Breeder.summaryUri), model: Breeder },
          ]
      }
    },
}
</script>
