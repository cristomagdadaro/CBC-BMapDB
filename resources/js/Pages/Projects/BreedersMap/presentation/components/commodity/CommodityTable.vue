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
        currentUserId() {
            return this.$page?.props?.auth?.user?.id || null;
        }
    },
    methods: {
        // A row is owned if commodity.user_id matches current user or the related breeder.user_id matches
        isOwner(row) {
            const uid = this.currentUserId;
            if (!uid || !row) return false;
            if (row.user_id && Number(row.user_id) === Number(uid)) return true;
            const breederUserId = row?.breeder?.user_id ?? null;
            return breederUserId && Number(breederUserId) === Number(uid);
        },
        rowCanUpdate(row) {
            // Only allow update when row is owned
            return this.isOwner(row);
        },
        rowCanDelete(row) {
            // Only allow delete when row is owned
            return this.isOwner(row);
        }
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
        :row-can-update="rowCanUpdate"
        :row-can-delete="rowCanDelete"
    />
</template>

<style scoped>

</style>
