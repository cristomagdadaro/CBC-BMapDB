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
            commodityTable: 'commodities',
            breederTable: 'breeders',
        }
    },
    async created() {
        await this.getBreedersDataView();
        await this.getCommoditiesDataView();
    },
    methods: {
        async getBreedersDataView() {
            this.instance = new BMapSettingApiService(route('api.dataview.show')+'/'+this.breederTable);
            this.responseBreeder = (await this.instance.get({})).data?.[0];
            this.convertToDataViewClass('breeders');
        },
        async getCommoditiesDataView() {
            this.instance = new BMapSettingApiService(route('api.dataview.show')+'/'+this.commodityTable);
            this.responseCommodity = (await this.instance.get({})).data?.[0];
            this.convertToDataViewClass('commodities');
        },
        async saveForm(model) {
            this.instance = new BMapSettingApiService('/api/data-view'+'/'+model);
            console.log(await this.instance.put(this.form));
        },
        convertToDataViewClass(table) {
            if (!this.responseBreeder || typeof this.responseBreeder !== "object") {
                console.warn("responseBreeder is not in the expected JSON format");
                return;
            }

            if (table === this.breederTable)
                Object.keys(this.responseBreeder).forEach(key => {
                    if (this.responseBreeder[key]) {
                        this.responseBreeder[key] = new DataView(this.responseBreeder[key]);
                    }
                });
            else if (table === this.commodityTable)
                Object.keys(this.responseCommodity).forEach(key => {
                    if (this.responseCommodity[key]) {
                        this.responseCommodity[key] = new DataView(this.responseCommodity[key]);
                    }
                });
        },
        getVisibility(dataView) {
            return (new this.DataView(dataView))?.getVisibilityGuard;
        },
        isColumnVisible(guard, column, table) {
            if (table === this.breederTable)
                return this.responseBreeder?.[guard].getVisibleColumns.includes(column);
            else if (table === this.commodityTable)
                return this.responseCommodity?.[guard].getVisibleColumns.includes(column);
        },
        toggleChecked(colGroup, col, model) {
            if (!this.form) {
                this.form = DataView.updateForm(colGroup);
            }

            if (this.form.columns.includes(col))
                this.form.columns =  this.form.columns.filter((item) => item !== col);
            else
                this.form.columns.push(col);

            this.form.uuid = colGroup.uuid;
            this.form.model = model;
            this.form.visibility_guard = this.getVisibility(colGroup);
        }
    },
    computed: {
        DataView() {
            return DataView;
        },
    }
}
</script>

<template>
    <div class="flex flex-col gap-5">

        <div v-if="responseBreeder" class="p-3 bg-gray-100 border">
            <h2 class="font-bold text-xl">Breeders Column View</h2>
            <template v-for="colGroup in responseBreeder">
                <form class="w-full p-2 flex flex-col gap-2" @submit.prevent="saveForm(getVisibility(colGroup), breederTable)" >
                    <div class="flex flex-col leading-none">
                        <label class="font-semibold text-lg text-gray-700">Guard: {{ getVisibility(colGroup) }}</label>
                        <label class="text-sm text-gray-600">{{ colGroup.uuid }}</label>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <template v-for="column in colGroup.getDefaultColumns">
                            <div class="flex gap-2 items-center border p-1 px-2 w-fit rounded shadow-sm bg-white" @click="toggleChecked(colGroup, column, colGroup.model)">
                                <checkbox :value="column" :checked="isColumnVisible(getVisibility(colGroup), column, breederTable)" @update:checked="toggleChecked(colGroup, column, colGroup.model)"/>
                                <label> {{column}} </label>
                            </div>
                        </template>
                        <primary-button @click.prevent="saveForm(breederTable)">
                            <span>Save</span>
                        </primary-button>
                    </div>
                </form>
            </template>
        </div>
        <div v-if="responseCommodity" class="p-3 bg-gray-100 border">
            <h2 class="font-bold text-xl">Commodities Column View</h2>
            <template v-for="colGroup in responseCommodity">
                <form class="w-full p-2 flex flex-col gap-2" @submit.prevent="saveForm(getVisibility(colGroup), commodityTable)" >
                    <div class="flex flex-col leading-none">
                        <label class="font-semibold text-lg text-gray-700">Guard: {{ getVisibility(colGroup) }}</label>
                        <label class="text-sm text-gray-600">{{ colGroup.uuid }}</label>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <template v-for="column in colGroup.getDefaultColumns">
                            <div class="flex gap-2 items-center border p-1 px-2 w-fit rounded shadow-sm bg-white">
                                <checkbox :value="column" :checked="isColumnVisible(getVisibility(colGroup), column, commodityTable)" @update:checked="toggleChecked(colGroup, column, colGroup.model)"/>
                                <label> {{column}} </label>
                            </div>
                        </template>
                        <primary-button @click.prevent="saveForm(commodityTable)">Save</primary-button>
                    </div>
                </form>
            </template>
        </div>
    </div>
</template>

<style scoped>

</style>
