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
        },
    },
    computed: {
        BreedersMapPages() {
            return BreedersMapPages
        },
        BaseURL() {
            let temp = this.BreedersMapPages.api.commodity.path;

            const queryParams = [];

            if (this.params.filter) {
                queryParams.push(`filter=${this.params.filter}`);
            }

            if (this.params.search) {
                queryParams.push(`search=${this.params.search}`);
            }

            if (this.params.is_exact) {
                queryParams.push(`is_exact=${this.params.is_exact}`);
            }

            if (queryParams.length > 0) {
                temp += '?' + queryParams.join('&');
            }

            console.log(temp);
            return temp;
        }
    },
    components: {CRCMDatatable}
}
</script>

<template>
<!--    <h1 class="h1 text-center font-semibold uppercase select-none">Commodities Database</h1>-->
    <CRCMDatatable
        :base-url="BaseURL"
        :base-model="BreedersMapPages.api.commodity.model"
        :add-form="BreedersMapPages.api.commodity.create.component"
        :edit-form="BreedersMapPages.api.commodity.edit.component"
        :import-modal="BreedersMapPages.api.commodity.import.component"
        :view-form="BreedersMapPages.api.commodity.view.path"
    />
    {{$page.props.params}}
</template>

<style scoped>

</style>
