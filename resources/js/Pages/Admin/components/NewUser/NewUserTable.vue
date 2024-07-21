<script>
import {Permission} from "@/Pages/constants.ts";
import CRCMDatatable from "@/Components/CRCMDatatable/CRCMDatatable.vue";
import {BreedersMapPages} from "@/Pages/Projects/BreedersMap/components/components.js";

export default {
    name: "NewUserTable",
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
<!--Url For Only Unverified Account-->
<!--BreedersMapPages.api.user.path + '?filter=email_verified_at&search=null&is_exact=true'-->
<template>
    <CRCMDatatable
        :base-url="BreedersMapPages.api.user.path"
        :base-model="BreedersMapPages.api.user.model"
        :can-create="canCreate"
        :can-update="canUpdate"
        :can-delete="canDelete"
        :can-view="canView"
    />
</template>

<style scoped>

</style>
