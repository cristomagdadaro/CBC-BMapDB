<script>
import {Permission} from "@/Pages/constants.ts";
import CRCMDatatable from "@/Components/CRCMDatatable/CRCMDatatable.vue";
import {AdminPages} from "@/Pages/Admin/components/components.js";
import ApiService from "@/Modules/core/infrastructure/ApiService";
import Notification from "@/Components/Modal/Notification/Notification";
import { TopActionBtn } from "@/Components/CRCMDatatable/Components";
import RefreshIcon from "@/Components/Icons/RefreshIcon.vue";

export default {
    name: "NewUserTable",
    components: {CRCMDatatable, TopActionBtn, RefreshIcon},
    data() {
        return {
            regeneratingIds: [],
        };
    },
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
        roleNames() {
            const roles = this.$page?.props?.auth?.user?.roles || [];
            return roles.map(role => role?.name ?? role).filter(Boolean);
        },
        isAdmin() {
            return this.roleNames.includes('Administrator');
        },
        isFocal() {
            return this.roleNames.includes('Focal Person');
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
    methods: {
        canRegenerate(row) {
            if (!row?.id) return false;
            if (row?.email_verified_at) return false;
            return this.isAdmin || this.isFocal;
        },
        isRegenerating(row) {
            return this.regeneratingIds.includes(row?.id);
        },
        async regenerateInvite(row) {
            if (!this.canRegenerate(row) || this.isRegenerating(row)) return;
            const userId = row?.id;
            if (row?.email_verified_at) {
                Notification.pushNotification({
                    title: 'Invitation',
                    message: 'User already verified. Regeneration not available.',
                    type: 'warning',
                    timeout: 8000,
                    show: true
                });
                return;
            }
            if (!userId) {
                Notification.pushNotification({
                    title: 'Invitation',
                    message: 'Unable to regenerate link: missing user ID.',
                    type: 'warning',
                    timeout: 8000,
                    show: true
                });
                return;
            }

            this.regeneratingIds.push(userId);
            try {
                const svc = new ApiService(route('accept.breeder.role.regenerate', userId));
                const response = await svc.post({ expires: 60 });
                const acceptUrl = response?.data?.accept_url || null;

                if (!acceptUrl) {
                    Notification.pushNotification({
                        title: 'Invitation',
                        message: 'Failed to generate a new invite link.',
                        type: 'failed',
                        timeout: 8000,
                        show: true
                    });
                    return;
                }

                try {
                    await navigator.clipboard.writeText(acceptUrl);
                    Notification.pushNotification({
                        title: 'Invitation',
                        message: 'New invite link copied to clipboard.',
                        type: 'success',
                        timeout: 8000,
                        show: true
                    });
                } catch (err) {
                    Notification.pushNotification({
                        title: 'Invitation',
                        message: 'New invite link generated. Copy from the browser console if needed.',
                        type: 'warning',
                        timeout: 12000,
                        show: true
                    });
                    console.warn('Invite link:', acceptUrl);
                }
            } finally {
                this.regeneratingIds = this.regeneratingIds.filter(id => id !== userId);
            }
        },
    }
}
</script>
<!--Url For Only Unverified Account-->
<!--AdminPages.api.user.path + '?filter=email_verified_at&search=null&is_exact=true'-->
<template>
    <CRCMDatatable
        :base-url="AdminPages.api.user.path"
        :base-model="AdminPages.api.user.model"
        :add-form="AdminPages.api.user.create.component"
        :edit-form="AdminPages.api.user.edit.component"
        :view-form="AdminPages.api.user.view.path"
        :can-create="canCreate"
        :can-update="canUpdate"
        :can-delete="canDelete"
        :can-view="canView"
    >
        <template #rowActions="{ row, showIconText }">
            <top-action-btn
                v-if="canRegenerate(row)"
                class="bg-refresh"
                :title="isRegenerating(row) ? 'Generating link…' : 'Regenerate invite link'"
                :showIconText="showIconText"
                @click="regenerateInvite(row)"
            >
                <template #icon>
                    <refresh-icon class="h-auto sm:w-4 w-3" :class="isRegenerating(row) ? 'animate-spin' : ''" />
                </template>
                <template #iconText>Invite</template>
            </top-action-btn>
        </template>
        <template #rowActionsMenu="{ row }">
            <button
                v-if="canRegenerate(row)"
                class="flex gap-1 p-1 items-center hover:bg-gray-200"
                :disabled="isRegenerating(row)"
                @click="regenerateInvite(row)"
            >
                <refresh-icon class="h-auto sm:w-5 w-4 p-0.5 text-refresh" :class="isRegenerating(row) ? 'animate-spin' : ''" />
                <span>Regenerate Invite</span>
            </button>
        </template>
    </CRCMDatatable>
</template>

<style scoped>

</style>
