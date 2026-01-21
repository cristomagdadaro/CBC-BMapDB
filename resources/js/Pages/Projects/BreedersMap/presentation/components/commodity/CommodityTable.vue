<script>
import CRCMDatatable from "@/Components/CRCMDatatable/CRCMDatatable.vue";
import { BreedersMapPages } from "@/Pages/Projects/BreedersMap/components/components.js";
import {Permission} from "@/Pages/constants.ts";
import User from "@/Modules/core/domain/auth/User.js";
import ApiService from "@/Modules/core/infrastructure/ApiService";
import { BreedersMapEndpoints } from "@/Pages/Projects/BreedersMap/infrastructure/BreedersMapEndpoints";
import { TopActionBtn } from "@/Components/CRCMDatatable/Components";
import CheckallIcon from "@/Components/Icons/CheckallIcon.vue";
import LikeIcon from "@/Components/Icons/LikeIcon.vue";
import LoaderIcon from "@/Components/Icons/LoaderIcon.vue";

export default {
    name: "CommodityTable",
    data() {
        return {
            approvingIds: [],
            disapprovingIds: [],
        };
    },
    props: {
        baseUrl: {
            type: String,
            required: false,
            default: null,
        },
        params: {
            type: Object,
            required: false,
        }
    },
    computed: {
        BreedersMapEndpoints() {
            return BreedersMapEndpoints
        },
        BreedersMapPages() {
            return BreedersMapPages
        },
        Permission() {
            return Permission;
        },
        canCreate() {
            return this.$page.props.permissions.breedersmap.commodity[Permission.CREATE];
        },
        canUpdate() {
            const hasPerm = this.$page.props.permissions.breedersmap.commodity[Permission.UPDATE];
            return hasPerm || this.isAdmin || this.isFocal;
        },
        canDelete() {
            const hasPerm = this.$page.props.permissions.breedersmap.commodity[Permission.DELETE];
            return hasPerm || this.isAdmin || this.isFocal;
        },
        canView() {
            return this.$page.props.permissions.breedersmap.commodity[Permission.VIEW];
        },
        currentUserId() {
            return this.$page?.props?.auth?.user?.id || null;
        },
        isAdmin() {
            return (new User(this.$page?.props?.auth?.user)).isAdmin
        },
        isFocal() {
            const roles = this.$page?.props?.auth?.user?.roles || [];
            return (new User(this.$page?.props?.auth?.user)).getRole === 'Focal Person' || roles.includes('Focal Person');
        },
        currentUserAffiliationId() {
            return this.$page?.props?.auth?.user?.affiliated?.id || null;
        },
        computedParams() {
            // Merge incoming params with default relation include for breeder
            const base = this.params || {};
            if (!base.with) {
                return { ...base, with: 'breeder' };
            }
            // Avoid duplicates
            const withVal = Array.isArray(base.with) ? base.with : String(base.with).split(',').map(s => s.trim()).filter(Boolean);
            if (!withVal.includes('breeder')) withVal.push('breeder');
            return { ...base, with: withVal.join(',') };
        }
    },
    methods: {
        // A row is owned if commodity.user_id matches current user or the related breeder.user_id matches
        isOwner(row) {
            const uid = this.currentUserId;
            if (!uid || !row) return false;
            if (row.user_id && Number(row.user_id) === Number(uid)) return true;
            const breederUserId = row?.breeder?.user_id ?? null;
            return breederUserId && Number(breederUserId) === Number(uid);
        },
        isSameInstituteCommodity(row) {
            const userAff = Number(this.currentUserAffiliationId);
            const rowAff = Number(row?.breeder?.affiliated?.id ?? null);
            return !!userAff && !!rowAff && userAff === rowAff;
        },
        rowCanUpdate(row) {
            // Admin can always update; Focal Person can update within institute; Breeder can update own
            if (this.isAdmin) return true;
            if (this.isFocal && this.isSameInstituteCommodity(row)) return true;
            return this.isOwner(row);
        },
        rowCanDelete(row) {
            // Admin can always delete; Focal Person can delete within institute; Breeder can delete own
            if (this.isAdmin) return true;
            if (this.isFocal && this.isSameInstituteCommodity(row)) return true;
            return this.isOwner(row);
        },
        canApprove(row) {
            return !!row && !row.approved_at && (this.isAdmin || this.isFocal) && this.rowCanUpdate(row);
        },
        canDisapprove(row) {
            return !!row && !!row.approved_at && (this.isAdmin || this.isFocal) && this.rowCanUpdate(row);
        },
        isApproving(row) {
            return !!row && this.approvingIds.includes(row.id);
        },
        isDisapproving(row) {
            return !!row && this.disapprovingIds.includes(row.id);
        },
        async approveCommodity(row) {
            if (!this.canApprove(row) || this.isApproving(row)) return;
            this.approvingIds.push(row.id);
            try {
                const svc = new ApiService(route(BreedersMapEndpoints.commodity.approveUri, row.id));
                await svc.put({});
                await this.$refs.datatable?.dt?.refresh();
            } finally {
                this.approvingIds = this.approvingIds.filter(id => id !== row.id);
            }
        },
        async disapproveCommodity(row) {
            if (!this.canDisapprove(row) || this.isDisapproving(row)) return;
            this.disapprovingIds.push(row.id);
            try {
                const svc = new ApiService(route(BreedersMapEndpoints.commodity.disapproveUri, row.id));
                await svc.put({});
                await this.$refs.datatable?.dt?.refresh();
            } finally {
                this.disapprovingIds = this.disapprovingIds.filter(id => id !== row.id);
            }
        }
    },
    components: {CRCMDatatable, TopActionBtn, CheckallIcon, LikeIcon, LoaderIcon}
}
</script>

<template>
    <CRCMDatatable
        ref="datatable"
        :base-url="baseUrl ?? BreedersMapPages.api.commodity.path"
        :base-model="BreedersMapPages?.api?.commodity?.model || ''"
        :add-form="BreedersMapPages?.api?.commodity?.create?.component || ''"
        :edit-form="BreedersMapPages?.api?.commodity?.edit?.component || ''"
        :import-modal="BreedersMapPages?.api?.commodity?.import?.component || ''"
        :view-form="BreedersMapPages?.api?.commodity?.view?.path || ''"
        :can-create="canCreate"
        :can-update="canUpdate"
        :can-delete="canDelete"
        :can-view="canView"
        :params="computedParams"
        :row-can-update="rowCanUpdate"
        :row-can-delete="rowCanDelete"
    >
        <template #rowActions="{ row, showIconText }">
            <top-action-btn
                v-if="canApprove(row)"
                class="bg-cbc-yellow-green"
                :class="isApproving(row) ? 'opacity-70 cursor-not-allowed' : ''"
                title="Approve"
                :show-icon-text="showIconText"
                @click="approveCommodity(row)"
            >
                <template #icon>
                    <loader-icon v-if="isApproving(row)" class="h-auto sm:w-4 w-3 animate-spin text-white" />
                    <like-icon v-else class="h-auto sm:w-4 w-3 text-white" />
                </template>
                <template #iconText>Approve</template>
            </top-action-btn>
            <top-action-btn
                v-if="canDisapprove(row)"
                class="bg-delete"
                :class="isDisapproving(row) ? 'opacity-70 cursor-not-allowed' : ''"
                title="Disapprove"
                :show-icon-text="showIconText"
                @click="disapproveCommodity(row)"
            >
                <template #icon>
                    <loader-icon v-if="isDisapproving(row)" class="h-auto sm:w-4 w-3 animate-spin text-white" />
                    <like-icon v-else class="h-auto sm:w-4 w-3 rotate-180 text-white" />
                </template>
                <template #iconText>Disapprove</template>
            </top-action-btn>
        </template>
    </CRCMDatatable>
</template>

<style scoped>

</style>
