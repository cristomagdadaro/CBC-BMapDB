<script>
import { BreedersMapPages } from "@/Pages/Projects/BreedersMap/components/components.js";
import CRCMDatatable from "@/Components/CRCMDatatable/CRCMDatatable.vue";
import ApiService from "@/Modules/core/infrastructure/ApiService";

export default {
    name: "BreedersTable",
    computed: {
        BreedersMapPages() {
            return BreedersMapPages
        },
        canCreate() {
            return this.isAdmin || this.isFocal;
        },
        canUpdate() {
            return this.isAdmin || this.isFocal;
        },
        canDelete() {
            return this.isAdmin || this.isFocal;
        },
        canView() {
            return this.isAdmin || this.isFocal || this.isBreeder || this.isResearcher;
        },
        roleNames() {
            const roles = this.$page?.props?.auth?.user?.roles || [];
            return roles.map(role => role?.name ?? role).filter(Boolean);
        },
        isAdmin() {
            return this.roleNames.includes('Administrator');
        },
        isFocal() {
            return this.roleNames.includes('Focal Person');
        },
        isBreeder() {
            return this.roleNames.includes('Breeder');
        },
        isResearcher() {
            return this.roleNames.includes('Researcher');
        },
        currentUserAffiliationId() {
            return this.$page?.props?.auth?.user?.affiliated?.id || null;
        }
    },
    methods: {
        isSameInstituteBreeder(row) {
            const userAff = Number(this.currentUserAffiliationId);
            const rowAff = Number(row?.affiliated?.id ?? null);
            return !!userAff && !!rowAff && userAff === rowAff;
        },
        rowCanUpdate(row) {
            if (this.isAdmin) return true;
            if (this.isFocal && this.isSameInstituteBreeder(row)) return true;
            return false;
        },
        rowCanDelete(row) {
            if (this.isAdmin) return true;
            if (this.isFocal && this.isSameInstituteBreeder(row)) return true;
            return false;
        }
    },
    components: {
        CRCMDatatable,
    },
};
</script>

<template>
    <CRCMDatatable
        :base-url="BreedersMapPages.api.breeder.path"
        :base-model="BreedersMapPages.api.breeder.model"
        :add-form="BreedersMapPages.api.breeder.create.component"
        :edit-form="BreedersMapPages.api.breeder.edit.component"
        :view-form="BreedersMapPages.api.breeder.view.path"
        :import-modal="BreedersMapPages?.api?.breeder?.import?.component || ''"
        :can-create="canCreate"
        :can-update="canUpdate"
        :can-delete="canDelete"
        :can-view="canView"
        :row-can-update="rowCanUpdate"
        :row-can-delete="rowCanDelete"
    />
</template>
