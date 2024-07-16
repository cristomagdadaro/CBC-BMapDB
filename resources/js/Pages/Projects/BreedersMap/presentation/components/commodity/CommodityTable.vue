<script>
import CRCMDatatable from "@/Components/CRCMDatatable/CRCMDatatable.vue";
import { BreedersMapPages } from "@/Pages/Projects/BreedersMap/components/components.js";

export default {
    name: "CommodityTable",
    props: {
        params: {
            type: Object,
            default: () => {
                return {
                    filter: null,
                    is_exact: null,
                    search: null,
                }
            }
        }
    },
    computed: {
        BreedersMapPages() {
            return BreedersMapPages
        },
        BaseURL() {
            if (this.params.filter && this.params.search && this.params.is_exact)
                return this.BreedersMapPages.api.commodity.path + '?filter=' + this.params.filter + '&search=' + this.params.search + '&is_exact=' + this.params.is_exact
            return this.BreedersMapPages.api.commodity.path
        }
    },
    components: {CRCMDatatable}
}
</script>

<template>
    <h1 class="h1 text-center font-semibold uppercase select-none">Commodities Database</h1>
    <CRCMDatatable
        :custom-params="params"
        :base-url="BaseURL"
        :base-model="BreedersMapPages.api.commodity.model"
        :add-form="BreedersMapPages.api.commodity.create.component"
        :edit-form="BreedersMapPages.api.commodity.edit.component"
        :import-modal="BreedersMapPages.api.commodity.import.component"
    />
</template>

<style scoped>

</style>
