<template>
    <Head title="Breeder's Map" />
    <app-layout>
        {{ $page.props.permissions }}
        <Tab :tabs="tabs" v-if="$page.props.auth.user">
            <template #tab1>
                <breeders-table />
            </template>
            <template #tab2>
                <commodity-table />
            </template>
            <template #tab3>
                <div class="p-2 relative">
<!--                    <h1 class="h1 text-center font-semibold uppercase select-none">Commodities Geographical Map</h1>-->
                    <Map :base-url="route('api.commodities.noPage')" />
                </div>
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

export default {
    components: {
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
    },
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
