<script>
import CRCMDatatable from "@/Components/CRCMDatatable/CRCMDatatable.vue";
import {TWGPages} from "@/Pages/Projects/TWG/components/components.js";
import {Permission} from "@/Pages/constants.ts";

export default {
    name: "ProductsTable",
    computed: {
        TWGPages() {
            return TWGPages
        },
        Permission() {
            return Permission;
        },
        canCreate() {
            return this.$page.props.permissions.twgdb.product[Permission.CREATE];
        },
        canUpdate() {
            return this.$page.props.permissions.twgdb.product[Permission.UPDATE];
        },
        canDelete() {
            return this.$page.props.permissions.twgdb.product[Permission.DELETE];
        },
        canView() {
            return this.$page.props.permissions.twgdb.product[Permission.VIEW];
        },
    },
    components: {CRCMDatatable}
}
</script>

<template>
    <CRCMDatatable
        :base-url="TWGPages.api.product.path"
        :base-model="TWGPages.api.product.model"
        :add-form="TWGPages.api.product.create.component"
        :edit-form="TWGPages.api.product.edit.component"
        :can-create="canCreate"
        :can-update="canUpdate"
        :can-delete="canDelete"
        :can-view="canView"
    />
</template>

<style scoped>

</style>
