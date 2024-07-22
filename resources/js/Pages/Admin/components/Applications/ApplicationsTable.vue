<script>
import CRCMDatatable from "@/Components/CRCMDatatable/CRCMDatatable.vue";
import {BreedersMapPages} from "@/Pages/Projects/BreedersMap/components/components.js";
import {Permission} from "@/Pages/constants.ts";

export default {
    name: "ApplicationsTable",
    components: {CRCMDatatable},
    props: {
        params: {
            type: Object,
            default: () => {
                return {
                    filter: null,
                    is_exact: null,
                    search: null,
                }
            }
        },
    },
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
    }
}
</script>

<template>
    {{BreedersMapPages.api.app.view.path}}
    <CRCMDatatable
        :base-url="BreedersMapPages.api.app.path"
        :base-model="BreedersMapPages.api.app.model"
        :edit-form="BreedersMapPages.api.app.edit.component"
        :add-form="BreedersMapPages.api.app.create.component"
        :view-form="BreedersMapPages.api.app.view.path"
        :can-create="canCreate"
        :can-update="canUpdate"
        :can-delete="canDelete"
        :can-view="canView"
    />
</template>

<style scoped>

</style>
