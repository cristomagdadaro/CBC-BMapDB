<script>
import { BreedersMapPages } from "@/Pages/Projects/BreedersMap/components/components.js";
import CRCMDatatable from "@/Components/CRCMDatatable/CRCMDatatable.vue";
import {Permission} from "@/Pages/constants.ts";

export default {
    name: "BreedersTable",
    computed: {
        BreedersMapPages() {
            return BreedersMapPages
        },
        Permission() {
            return Permission;
        },
        canCreate() {
            return this.$page.props.permissions.breedersmap.breeder[Permission.CREATE];
        },
        canUpdate() {
            const hasPerm = this.$page.props.permissions.breedersmap.breeder[Permission.UPDATE];
            return hasPerm || this.isAdmin || this.isFocal;
        },
        canDelete() {
            const hasPerm = this.$page.props.permissions.breedersmap.breeder[Permission.DELETE];
            return hasPerm || this.isAdmin || this.isFocal;
        },
        canView() {
            return this.$page.props.permissions.breedersmap.breeder[Permission.VIEW];
        },
        isAdmin() {
            const roles = this.$page?.props?.auth?.user?.roles || [];
            return roles.some(r => r.name === 'Administrator');
        },
        isFocal() {
            const roles = this.$page?.props?.auth?.user?.roles || [];
            return roles.some(r => r.name === 'Focal Person');
        },
        currentUserAffiliationId() {
            return this.$page?.props?.auth?.user?.affiliated?.id || null;
        }
    },
    methods: {
        isSameInstituteBreeder(row) {
            const userAff = Number(this.currentUserAffiliationId);
            const rowAff = Number(row?.affiliation ?? null);
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
