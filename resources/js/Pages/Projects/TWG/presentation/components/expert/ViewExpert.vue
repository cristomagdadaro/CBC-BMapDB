<script>
import { Head } from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import CommodityTable from "@/Pages/Projects/BreedersMap/presentation/components/commodity/CommodityTable.vue";
import Tab from "@/Components/Tab/Tab.vue";
import Map from "@/Pages/Projects/BreedersMap/presentation/components/map/Map.vue";
import Expert from "@/Pages/Projects/TWG/domain/Expert";

export default {
    name: "ViewExpert",
    props: {
        expert: {
            type: Object,
            required: false,
            default: null
        }
    },
    computed: {
        Expert() {
            return Expert
        }
    },
    data() {
        return {
            data: null,
            axiosInstance: null,
            tabs: [
                {
                    name: "tab1",
                    label: "Projects",
                    active: true,
                    route: { name: 'projects.twg.experts' },
                },{
                    name: "tab2",
                    label: "Products",
                    active: false,
                    route: { name: 'projects.twg.products' },
                },{
                    name: "tab3",
                    label: "Services",
                    active: false,
                    route: { name: 'projects.twg.services' },
                },
            ],
        }
    },
    components: {Map, Tab, CommodityTable, AppLayout, Head },
}
</script>

<template>
    <Head title="Expert View" />
    <app-layout>
        <div class="min-h-screen bg-transparent min-w-full m-2 p-2">
            <div v-if="expert" class="flex flex-col">
                <h1 class="text-lg font-semibold uppercase select-none px-3 pb-2 mx-2">Expert Information</h1>
                <div class="border p-3 rounded-lg bg-white mx-2 grid sm:grid-cols-2 grid-cols-1">
                    <div class="flex gap-1">
                        <h2 class="h2 font-semibold select-none">Expert ID: </h2>
                        <p>{{ expert.id }}</p>
                    </div>
                    <div class="flex gap-1">
                        <h2 class="h2 font-semibold select-none">Expert: </h2>
                        <p>{{ expert.name }}</p>
                    </div>
                    <div class="flex gap-1">
                        <h2 class="h2 font-semibold select-none">Position: </h2>
                        <p>{{ expert.position }}</p>
                    </div>
                    <div class="flex gap-1">
                        <h2 class="h2 font-semibold select-none whitespace-nowrap">Education Attainment: </h2>
                        <p>{{ expert.educ_level }}</p>
                    </div>
                    <div class="flex gap-1">
                        <h2 class="h2 font-semibold select-none">Expertise: </h2>
                        <p>{{ expert.expertise }}</p>
                    </div>
                    <div class="flex gap-1">
                        <h2 class="h2 font-semibold select-none">Research Interest: </h2>
                        <p>{{ expert.research_interest }}</p>
                    </div>
                    <div class="flex gap-1">
                        <h2 class="h2 font-semibold select-none">Mobile No.: </h2>
                        <p>{{ expert.mobile }}</p>
                    </div>
                    <div class="flex gap-1">
                        <h2 class="h2 font-semibold select-none">Email: </h2>
                        <p>{{ expert.email }}</p>
                    </div>
                </div>
            </div>
        </div>
        <Tab :tabs="tabs">
            <template v-slot:tab1>

            </template>
        </Tab>
    </app-layout>
</template>

<style scoped>

</style>
