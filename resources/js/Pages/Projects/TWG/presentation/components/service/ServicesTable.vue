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
    <h1 class="h1 text-center font-semibold uppercase">Services/Protocols</h1>
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
