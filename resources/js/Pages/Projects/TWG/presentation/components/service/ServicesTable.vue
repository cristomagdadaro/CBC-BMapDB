<script>
import CRCMDatatable from "@/Components/CRCMDatatable/CRCMDatatable.vue";
import {TWGPages} from "@/Pages/Projects/TWG/components/components.js";
import {Permission} from "@/Pages/constants.ts";

export default {
    name: "ServicesTable",
    computed: {
        TWGPages() {
            return TWGPages
        },
        Permission() {
            return Permission;
        },
        canCreate() {
            return this.$page.props.permissions.twgdb.services[Permission.CREATE];
        },
        canUpdate() {
            return this.$page.props.permissions.twgdb.services[Permission.UPDATE];;
        },
        canDelete() {
            return this.$page.props.permissions.twgdb.services[Permission.DELETE];
        },
        canView() {
            return this.$page.props.permissions.twgdb.services[Permission.VIEW];
        },
    },
    components: {CRCMDatatable}
}
</script>

<template>
    <CRCMDatatable
        :base-url="TWGPages.api.service.path"
        :base-model="TWGPages.api.service.model"
        :add-form="TWGPages.api.service.create.component"
        :edit-form="TWGPages.api.service.edit.component"
        :can-create="canCreate"
        :can-update="canUpdate"
        :can-delete="canDelete"
        :can-view="canView"
    />
</template>

<style scoped>

</style>
