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
        :can-create="canCreate"
        :can-update="canUpdate"
        :can-delete="canDelete"
        :can-view="canView"
    />
</template>
