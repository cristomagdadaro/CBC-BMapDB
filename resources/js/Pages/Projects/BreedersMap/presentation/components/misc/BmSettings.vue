<script>
import BMapSettingApiService from "@/Pages/Projects/BreedersMap/infrastructure/BMapSettingApiService";
import DataView from "@/Pages/Admin/domain/DataView";
import Checkbox from "@/Components/Checkbox.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

export default {
    name: 'BmSettings',
    components: {PrimaryButton, Checkbox},
    data() {
        return {
            instance: null,
            responseBreeder: null,
            responseCommodity: null,
            form: null,
        }
    },
    async created() {
        await this.getCommoditiesDataView();
        await this.getBreedersDataView();
        this.form = [];
    },
    methods: {
        async getBreedersDataView() {
            this.instance = new BMapSettingApiService(route('api.dataview.show')+'/breeders');
            this.responseBreeder = (await this.instance.get({})).data;
        },
        async getCommoditiesDataView() {
            this.instance = new BMapSettingApiService(route('api.dataview.show')+'/commodities');
            this.responseCommodity = (await this.instance.get({})).data;
        },
        async saveForm(guard, model) {
            this.instance = new BMapSettingApiService(route('api.dataview.update')+'/'+model);
            console.log(await this.instance.put({
                model: model,
                visibility_guard: guard,
                columns: this.form[guard]?.join(','),
            }));
        },
        getVisibility(dataView) {
            return (new this.DataView(dataView)).getVisibilityGuard;
        },
        toggleChecked(guard, value) {
            if (!this.form[guard]) {
                this.form[guard] = [];
            }
            if (this.form[guard].includes(value)) {
                this.form[guard] = this.form[guard].filter((v) => v !== value); // Remove if exists
            } else {
                this.form[guard].push(value); // Add if not exists
            }

            console.log(this.form);
        }
    },
    computed: {
        DataView() {
            return DataView;
        }
    }
}
</script>

<template>
    <div class="flex flex-col gap-5">
        {{ responseBreeder }}
<!--        <div v-if="responseBreeder" class="p-3 bg-gray-100 border">
            <h2 class="font-bold">Breeders Column View</h2>
            <div v-for="dataView in responseBreeder">
                <template v-for="guard in dataView" >
                    <form class="w-full p-2">
                        <label class="font-semibold">Guard: {{ getVisibility(guard) }}</label>
                        <div class="flex flex-wrap gap-1">
                            <template v-for="column in (new DataView(guard)).getColumns.split(',')">
                                <div class="flex gap-1 items-center border p-1 px-2 w-fit rounded shadow-sm bg-white">
                                    <checkbox :value="column" @update:checked="toggleChecked(getVisibility(guard), column)"/>
                                    <label> {{column}} </label>
                                </div>
                            </template>
                            <primary-button @click.prevent="saveForm(getVisibility(guard), 'breeders')">Save</primary-button>
                        </div>
                    </form>
                </template>
            </div>

        </div>
        <div v-if="responseCommodity" class="p-3 bg-gray-100 border">
            <h2 class="font-bold">Breeders Column View</h2>
            <div v-for="dataView in responseCommodity">
                <template v-for="guard in dataView" >
                    <div class="w-full p-2">
                        <label class="font-semibold">Guard: {{ (new DataView(guard)).getVisibilityGuard }}</label>
                        <div class="flex flex-wrap gap-1">
                            <template v-for="column in (new DataView(guard)).getColumns.split(',')">
                                <div class="flex gap-1 items-center border p-1 px-2 w-fit rounded shadow-sm bg-white">
                                    <checkbox  :value="column"/>
                                    <label> {{column}} </label>
                                </div>
                            </template>
                        </div>
                    </div>
                </template>
            </div>
        </div>-->
    </div>
</template>

<style scoped>

</style>
