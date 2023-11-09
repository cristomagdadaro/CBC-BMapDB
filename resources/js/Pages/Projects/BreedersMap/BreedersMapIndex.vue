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
        <div class="min-h-screen bg-gray-500 sm:p-4 p-1">
            <Tab :tabs="tabs" v-if="$page.props.auth.user">
                <template #tab1>
                    <div class="p-2">
                        <h1 class="h1">Breeders Personal Information</h1>
                        <CRCMDatatable
                            :base-url="BreedersMapPages.api.breeder.path"
                            :base-model="BreedersMapPages.api.breeder.model"
                            :add-form="BreedersMapPages.create.component"
                            :edit-form="BreedersMapPages.edit.component"
                        />
                    </div>
                </template>
                <template #tab2>
                    Tab 2
                </template>
                <template #tab3>
                    Tab 3
                </template>
            </Tab>
            <p v-else>Please login to view the data</p>

        </div>
        <div class="w-full sm:p-5 p-0">
            <div class="flex flex-wrap gap-2">
                <div id="map" class="border shadow-md"></div>
            </div>
        </div>
    </app-layout>

</template>
<style scoped>
#map {
    @apply sm:w-[50%] w-full h-screen z-0;
}
</style>
<script>
export default {
    data() {
      return {
        tabs: [
          {
            name: "tab1",
            label: "Breeders",
            active: true,
          },
          {
            name: "tab2",
            label: "Products",
            active: false,
          },
          {
            name: "tab3",
            label: "Services",
            active: false,
          },
        ],
      }
    },
}
</script>
