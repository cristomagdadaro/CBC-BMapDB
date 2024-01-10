<script setup>
import { Head } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import CRCMDatatable from "@/Components/CRCMDatatable/CRCMDatatable.vue";
import { BreedersMapPages } from "@/Pages/Projects/BreedersMap/components/components.js";
import Tab from "@/Components/Tab/Tab.vue";
</script>
<template>
    <Head title="Breeder's Map" />
    <app-layout>
        <div class="min-h-screen sm:p-3 p-0.5 bg-transparent">
            <Tab :tabs="tabs" v-if="$page.props.auth.user">
                <template #tab1>
                    <div class="p-2">
                        <h1 class="h1 text-center font-semibold uppercase">Breeders Database</h1>
                        <CRCMDatatable
                            :base-url="BreedersMapPages.api.breeder.path"
                            :base-model="BreedersMapPages.api.breeder.model"
                            :add-form="BreedersMapPages.api.breeder.create.component"
                            :edit-form="BreedersMapPages.api.breeder.edit.component"
                        />
                    </div>
                </template>
                <template #tab2>
                    <div class="p-2">
                        <h1 class="h1 text-center font-semibold uppercase">Commodities Database</h1>
                        <CRCMDatatable
                            :base-url="BreedersMapPages.api.commodity.path"
                            :base-model="BreedersMapPages.api.commodity.model"
                            :add-form="BreedersMapPages.api.commodity.create.component"
                            :edit-form="BreedersMapPages.api.commodity.edit.component"
                        />
                    </div>
                </template>
                <template #tab3>
                    <div class="p-2 relative">
                        <h1 class="h1 text-center font-semibold uppercase">Commodities Geographical Map</h1>
                        <Map />
                    </div>
                </template>
            </Tab>
            <p v-else>Please login to view the data</p>
        </div>
    </app-layout>
</template>
<style scoped>
#map {
    @apply sm:w-[50%] w-full h-screen z-0;
}
</style>
<script>
import Map from "@/Pages/Projects/BreedersMap/presentation/components/map/Map.vue";
export default {
    data() {
      return {
        tabs: [
          {
            name: "tab1",
            label: "Breeders",
            active: true,
          },{
            name: "tab2",
            label: "Commodities",
            active: false,
          },{
            name: "tab3",
            label: "Geo Map",
            active: false,
          },
        ],
      }
    },
}
</script>
