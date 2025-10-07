<script>
import CRCMDatatable from "@/Components/CRCMDatatable/CRCMDatatable.vue";
import { BreedersMapPages } from "@/Pages/Projects/BreedersMap/components/components.js";
import {Permission} from "@/Pages/constants.ts";
import User from "@/Modules/core/domain/auth/User.js";

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
            const hasPerm = this.$page.props.permissions.breedersmap.commodity[Permission.UPDATE];
            return hasPerm || this.isAdmin || this.isFocal;
        },
        canDelete() {
            const hasPerm = this.$page.props.permissions.breedersmap.commodity[Permission.DELETE];
            return hasPerm || this.isAdmin || this.isFocal;
        },
        canView() {
            return this.$page.props.permissions.breedersmap.commodity[Permission.VIEW];
        },
        currentUserId() {
            return this.$page?.props?.auth?.user?.id || null;
        },
        isAdmin() {
            return (new User(this.$page?.props?.auth?.user)).isAdmin
        },
        isFocal() {
            const roles = this.$page?.props?.auth?.user?.roles || [];
            return (new User(this.$page?.props?.auth?.user)).getRole === 'Focal Person' || roles.includes('Focal Person');
        },
        currentUserAffiliationId() {
            return this.$page?.props?.auth?.user?.affiliated?.id || null;
        },
        computedParams() {
            // Merge incoming params with default relation include for breeder
            const base = this.params || {};
            if (!base.with) {
                return { ...base, with: 'breeder' };
            }
            // Avoid duplicates
            const withVal = Array.isArray(base.with) ? base.with : String(base.with).split(',').map(s => s.trim()).filter(Boolean);
            if (!withVal.includes('breeder')) withVal.push('breeder');
            return { ...base, with: withVal.join(',') };
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
        isSameInstituteCommodity(row) {
            const userAff = Number(this.currentUserAffiliationId);
            const rowAff = Number(row?.breeder?.affiliated?.id ?? null);
            return !!userAff && !!rowAff && userAff === rowAff;
        },
        rowCanUpdate(row) {
            // Admin can always update; Focal Person can update within institute; Breeder can update own
            if (this.isAdmin) return true;
            if (this.isFocal && this.isSameInstituteCommodity(row)) return true;
            return this.isOwner(row);
        },
        rowCanDelete(row) {
            // Admin can always delete; Focal Person can delete within institute; Breeder can delete own
            if (this.isAdmin) return true;
            if (this.isFocal && this.isSameInstituteCommodity(row)) return true;
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
        :params="computedParams"
        :row-can-update="rowCanUpdate"
        :row-can-delete="rowCanDelete"
    />
</template>

<style scoped>

</style>
