<script>
import CRCMDatatable from "@/Components/CRCMDatatable/CRCMDatatable.vue";
import {TWGPages} from "@/Pages/Projects/TWG/components/components.js";
import {Permission} from "@/Pages/constants.ts";

export default {
    name: "ExpertsTable",
    computed: {
        TWGPages() {
            return TWGPages
        },
        Permission() {
            return Permission;
        },
        canCreate() {
            return this.$page.props.permissions.twgdb.expert[Permission.CREATE];
        },
        canUpdate() {
            return this.$page.props.permissions.twgdb.expert[Permission.UPDATE];;
        },
        canDelete() {
            return this.$page.props.permissions.twgdb.expert[Permission.DELETE];
        },
        canView() {
            return this.$page.props.permissions.twgdb.expert[Permission.VIEW];
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
