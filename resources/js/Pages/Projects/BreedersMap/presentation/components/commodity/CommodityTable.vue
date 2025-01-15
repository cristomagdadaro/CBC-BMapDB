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
            return this.$page.props.permissions.breedersmap.commodity[Permission.CREATE];
        },
        canUpdate() {
            return this.$page.props.permissions.breedersmap.commodity[Permission.UPDATE];
        },
        canDelete() {
            return this.$page.props.permissions.breedersmap.commodity[Permission.DELETE];
        },
        canView() {
            return this.$page.props.permissions.breedersmap.commodity[Permission.VIEW];
        },
    },
    components: {CRCMDatatable}
}
</script>

<template>
    <CRCMDatatable
        :base-url="baseUrl ?? BreedersMapPages.api.commodity.path"
        :base-model="BreedersMapPages?.api?.commodity?.model || ''"
        :add-form="BreedersMapPages?.api?.commodity?.create?.component || ''"
        :edit-form="BreedersMapPages?.api?.commodity?.edit?.component || ''"
        :import-modal="BreedersMapPages?.api?.commodity?.import?.component || ''"
        :view-form="BreedersMapPages?.api?.commodity?.view?.path || ''"
        :can-create="canCreate"
        :can-update="canUpdate"
        :can-delete="canDelete"
        :can-view="canView"
        :params="params"
    />
</template>

<style scoped>

</style>
