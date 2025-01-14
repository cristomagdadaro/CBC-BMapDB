<script>
import CRCMDatatable from "@/Components/CRCMDatatable/CRCMDatatable.vue";
import { BreedersMapPages } from "@/Pages/Projects/BreedersMap/components/components.js";
import {Permission} from "@/Pages/constants.ts";

export default {
    name: "CommodityTable",
    props: {
        baseUrl: {
            type: String,
            required: false,
            default: null,
        },
        params: {
            type: Object,
            required: false,
        }
    },
    computed: {
        BreedersMapPages() {
            return BreedersMapPages
        },
        Permission() {
            return Permission;
        },
        canCreate() {
            //return this.$page.props.permissions[Permission.CREATE];
            return true;
        },
        canUpdate() {
            //return this.$page.props.permissions[Permission.UPDATE];
            return true;
        },
        canDelete() {
            //return this.$page.props.permissions[Permission.DELETE];
            return true;
        },
        canView() {
            //return this.$page.props.permissions[Permission.VIEW];
            return true;
        },
    },
    components: {CRCMDatatable}
}
</script>

<template>
    <CRCMDatatable
        :base-url="baseUrl ? baseUrl + `?filter_by_parent_column=${params?.filter_by_parent_column || ''}&filter_by_parent_id=${params?.filter_by_parent_id || ''}` : BreedersMapPages.api.commodity.path"
        :base-model="BreedersMapPages?.api?.commodity?.model || ''"
        :add-form="BreedersMapPages?.api?.commodity?.create?.component || ''"
        :edit-form="BreedersMapPages?.api?.commodity?.edit?.component || ''"
        :import-modal="BreedersMapPages?.api?.commodity?.import?.component || ''"
        :view-form="BreedersMapPages?.api?.commodity?.view?.path || ''"
        :can-create="canCreate ?? false"
        :can-update="canUpdate ?? false"
        :can-delete="canDelete ?? false"
        :can-view="canView ?? false"
    />
</template>

<style scoped>

</style>
