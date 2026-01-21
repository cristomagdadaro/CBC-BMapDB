<script>
import CRCMDatatable from "@/Components/CRCMDatatable/CRCMDatatable.vue";
import {TWGPages} from "@/Pages/Projects/TWG/components/components.js";

export default {
    name: "ExpertsTable",
    computed: {
        TWGPages() {
            return TWGPages
        },
        roleNames() {
            const roles = this.$page?.props?.auth?.user?.roles || [];
            return roles.map(role => role?.name ?? role).filter(Boolean);
        },
        isAdmin() {
            return this.roleNames.includes('Administrator');
        },
        isTwgManager() {
            return this.roleNames.includes('TWG Manager');
        },
        canCreate() {
            return this.isAdmin || this.isTwgManager;
        },
        canUpdate() {
            return this.isAdmin || this.isTwgManager;
        },
        canDelete() {
            return this.isAdmin || this.isTwgManager;
        },
        canView() {
            return this.isAdmin || this.isTwgManager;
        },
    },
    components: {CRCMDatatable}
}
</script>

<template>
    <CRCMDatatable
        :base-url="TWGPages.api.expert.path"
        :base-model="TWGPages.api.expert.model"
        :add-form="TWGPages.api.expert.create.component"
        :edit-form="TWGPages.api.expert.edit.component"
        :view-form="TWGPages.api.expert.view.path"
        :import-modal="TWGPages.api.expert.import.component"
        :can-create="canCreate"
        :can-update="canUpdate"
        :can-delete="canDelete"
        :can-view="canView"
    />
</template>

<style scoped>

</style>
