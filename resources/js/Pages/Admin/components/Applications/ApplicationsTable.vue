<script>
import CRCMDatatable from "@/Components/CRCMDatatable/CRCMDatatable.vue";
import {AdminPages} from "@/Pages/Admin/components/components.js";
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
        AdminPages() {
            return AdminPages
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
    <div class="flex flex-col">
        <div>
            <h3 class="text-center font-bold">System Applications</h3>
            <CRCMDatatable
                :base-url="AdminPages.api.app.path"
                :base-model="AdminPages.api.app.model"
                :edit-form="AdminPages.api.app.edit.component"
                :add-form="AdminPages.api.app.create.component"
                :view-form="AdminPages.api.app.view.path"
                :can-create="canCreate"
                :can-update="canUpdate"
                :can-delete="canDelete"
                :can-view="canView"
            />
        </div>
        <div>
            <h3 class="text-center font-bold">User Roles and Permissions</h3>
            <CRCMDatatable
                :base-url="AdminPages.api.role.path"
                :base-model="AdminPages.api.role.model"
                :edit-form="AdminPages.api.role.edit.component"
                :add-form="AdminPages.api.role.create.component"
                :view-form="AdminPages.api.role.view.path"
                :can-create="canCreate"
                :can-update="canUpdate"
                :can-delete="canDelete"
                :can-view="canView"
            />
        </div>
    </div>
</template>

<style scoped>

</style>
