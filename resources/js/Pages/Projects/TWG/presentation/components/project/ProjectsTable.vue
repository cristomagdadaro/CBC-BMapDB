<script>
import CRCMDatatable from "@/Components/CRCMDatatable/CRCMDatatable.vue";
import {TWGPages} from "@/Pages/Projects/TWG/components/components.js";
import {Permission} from "@/Pages/constants.ts";

export default {
    name: "ProjectsTable",
    computed: {
        TWGPages() {
            return TWGPages
        },
        Permission() {
            return Permission;
        },
        canCreate() {
            return this.$page.props.permissions.twgdb.project[Permission.CREATE];
        },
        canUpdate() {
            return this.$page.props.permissions.twgdb.project[Permission.UPDATE];
        },
        canDelete() {
            return this.$page.props.permissions.twgdb.project[Permission.DELETE];
        },
        canView() {
            return this.$page.props.permissions.twgdb.project[Permission.VIEW];
        },
    },
    components: {CRCMDatatable}
}
</script>

<template>
    <CRCMDatatable
        :base-url="TWGPages.api.project.path"
        :base-model="TWGPages.api.project.model"
        :add-form="TWGPages.api.project.create.component"
        :edit-form="TWGPages.api.project.edit.component"
        :can-create="canCreate"
        :can-update="canUpdate"
        :can-delete="canDelete"
        :can-view="canView"
    />
</template>

<style scoped>

</style>
