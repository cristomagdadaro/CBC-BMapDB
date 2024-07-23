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
    <h1 class="h1 text-center font-semibold uppercase">Projects</h1>
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
