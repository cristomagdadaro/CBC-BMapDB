<script>
import BMapSettingApiService from "@/Pages/Projects/BreedersMap/infrastructure/BMapSettingApiService";
import DataView from "@/Pages/Admin/domain/DataView";
import Checkbox from "@/Components/Checkbox.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TransitionContainer from "@/Components/CustomDropdown/Components/TransitionContainer.vue";

export default {
    name: 'BmSettings',
    components: {TransitionContainer, PrimaryButton, Checkbox},
    data() {
        return {
            instance: null,
            responseBreeder: null,
            responseCommodity: null,
            responseApi: null,
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
        clearForm(){
          this.form = null;
        },
        async getBreedersDataView() {
            this.instance = new BMapSettingApiService(route('api.dataview.show')+'/'+this.breederTable);
            this.responseBreeder = (await this.instance.get({})).data;
            this.convertToDataViewClass('breeders');
        },
        async getCommoditiesDataView() {
            this.instance = new BMapSettingApiService(route('api.dataview.show')+'/'+this.commodityTable);
            this.responseCommodity = (await this.instance.get({})).data;
            this.convertToDataViewClass('commodities');
        },
        async saveForm(model) {
            this.instance = new BMapSettingApiService('/api/data-view'+'/'+model);
            this.responseApi = await this.instance.put(this.form);
            this.clearForm();
        },
        convertToDataViewClass(table) {
            if (!this.responseBreeder || typeof this.responseBreeder !== "object") {
                console.warn("responseBreeder is not in the expected JSON format. Converting...");
                this.responseBreeder = {}; // Convert to an empty object to avoid errors
                return;
            }


            if (table === this.breederTable && this.responseBreeder)
                Object.keys(this.responseBreeder).forEach(key => {
                    if (this.responseBreeder[key]) {
                        this.responseBreeder[key] = new DataView(this.responseBreeder[key]);
                    }
                });
            else if (table === this.commodityTable && this.responseCommodity)
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
            const visibleColumns =
                table === this.breederTable
                    ? this.responseBreeder?.[guard]?.getVisibleColumns
                    : table === this.commodityTable
                        ? this.responseCommodity?.[guard]?.getVisibleColumns
                        : [];

            return visibleColumns?.some((col) => col.endsWith(`.${column}`) || col === column);
        },
        toggleChecked(colGroup, col, model) {
            if (!this.form) {
                this.form = DataView.updateForm(colGroup);
            }

            if (this.form.columns?.some((column) => column.endsWith(`.${col}`) || col === column))
                this.form.columns = this.form.columns.filter((item) => !item.endsWith(`.${col}`) && item !== col);
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
    <div class="flex flex-col gap-3">
        <div class="text-lg leading-tight p-3 bg-gray-100 border rounded">
            <p class="mb-2">
                Customize which data points are accessible based on different access levels.
                Use the guide below to understand how each setting affects visibility.
                <span class="font-semibold">Note:</span> This setting does not affect data tables; it only applies to cards, summaries, and other visual elements.
            </p>


            <label class="text-xl font-bold block mb-1">Access Level Guards:</label>

            <ul class="list-disc list-inside space-y-1">
                <li>
                    <span class="font-bold">Public</span>: These columns are visible to everyone, including guests.
                </li>
                <li>
                    <span class="font-bold">Private</span>: Only you can view these columns.
                </li>
                <li>
                    <span class="font-bold">System</span>: These columns are restricted to authenticated users.
                </li>
            </ul>
        </div>

        <div v-if="responseBreeder && !instance.processing " class="p-3 bg-gray-100 border">
            <h2 class="font-bold text-xl">Breeders Column View</h2>
            <template v-for="colGroup in responseBreeder">
                <form class="w-full p-2 flex flex-col gap-2" @submit.prevent="saveForm(getVisibility(colGroup), breederTable)" >
                    <div class="flex flex-col leading-tight">
                        <label class="font-semibold text-lg text-gray-700">Guard: {{ getVisibility(colGroup) }}</label>
                        <label class="text-sm text-gray-600">{{ colGroup.uuid }}</label>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <template v-for="column in colGroup.getDefaultColumns">
                            <div class="flex gap-2 items-center border py-3 px-4 w-fit rounded shadow-sm bg-white text-lg" :class="{'border-cbc-dark-green':isColumnVisible(getVisibility(colGroup), column, breederTable)}">
                                <checkbox :value="column" :checked="isColumnVisible(getVisibility(colGroup), column, breederTable)" @update:checked="toggleChecked(colGroup, column, colGroup.model)"/>
                                <label> {{column}} </label>
                            </div>
                        </template>
                        <transition-container>
                            <div v-show="colGroup?.uuid === form?.uuid" class="flex flex-wrap gap-2">
                                <primary-button @click.prevent="saveForm(commodityTable)">
                                    Save
                                </primary-button>
                                <primary-button @click.prevent="clearForm">
                                    Cancel
                                </primary-button>
                            </div>
                        </transition-container>
                    </div>
                </form>
            </template>
        </div>
        <div v-if="responseCommodity" class="p-3 bg-gray-100 border">
            <h2 class="font-bold text-xl">Commodities Column View</h2>
            <template v-for="colGroup in responseCommodity">
                <form class="w-full p-2 flex flex-col gap-2" @submit.prevent="saveForm(getVisibility(colGroup), commodityTable)" >
                    <div class="flex flex-col leading-tight">
                        <label class="font-semibold text-lg text-gray-700">Guard: {{ getVisibility(colGroup) }}</label>
                        <label class="text-sm text-gray-600">{{ colGroup.uuid }}</label>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        <template v-for="column in colGroup.getDefaultColumns">
                            <div class="flex gap-2 items-center border py-3 px-4 w-fit rounded shadow-sm bg-white text-lg" :class="{'border-cbc-dark-green':isColumnVisible(getVisibility(colGroup), column, commodityTable)}">
                                <checkbox :value="column" :checked="isColumnVisible(getVisibility(colGroup), column, commodityTable)" @update:checked="toggleChecked(colGroup, column, colGroup.model)"/>
                                <label> {{column}} </label>
                            </div>
                        </template>
                        <transition-container>
                            <div v-show="colGroup?.uuid === form?.uuid" class="flex flex-wrap gap-2">
                                <primary-button @click.prevent="saveForm(commodityTable)">
                                    Save
                                </primary-button>
                                <primary-button @click.prevent="clearForm">
                                    Cancel
                                </primary-button>
                            </div>
                        </transition-container>
                    </div>
                </form>
            </template>
        </div>
    </div>
</template>

<style scoped>

</style>
