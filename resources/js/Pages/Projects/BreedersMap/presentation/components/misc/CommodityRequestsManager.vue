<template>
    <div class="p-6 bg-white rounded-lg shadow mt-4">
        <h2 class="text-xl font-semibold text-gray-800 mb-4">Commodity Add Requests</h2>
        
        <CRCMDatatable
            ref="datatable"
            :base-url="route('api.commodity-requests.index')"
            :base-model="CommodityRequest"
            :can-create="false"
            :can-update="false"
            :can-delete="false"
            :can-view="canView"
            :show-action-btns="true"
            :params="{}"
        >
            <template #rowActions="{ row, showIconText }">
                <top-action-btn
                    v-if="row.status === 'pending' && canUpdate"
                    class="bg-green-600"
                    title="Approve"
                    :show-icon-text="showIconText"
                    @click="updateStatus(row.id, 'approved')"
                >
                    <template #icon>
                        <like-icon class="h-auto sm:w-4 w-3 text-white" />
                    </template>
                    <template #iconText>Approve</template>
                </top-action-btn>

                <top-action-btn
                    v-if="row.status === 'pending' && canUpdate"
                    class="bg-red-600"
                    title="Reject"
                    :show-icon-text="showIconText"
                    @click="updateStatus(row.id, 'rejected')"
                >
                    <template #icon>
                        <like-icon class="h-auto sm:w-4 w-3 rotate-180 text-white" />
                    </template>
                    <template #iconText>Reject</template>
                </top-action-btn>
            </template>
        </CRCMDatatable>
    </div>
</template>

<script>
import CRCMDatatable from "@/Components/CRCMDatatable/CRCMDatatable.vue";
import CommodityRequest from "@/Pages/Projects/BreedersMap/domain/CommodityRequest";
import { TopActionBtn } from "@/Components/CRCMDatatable/Components";
import LikeIcon from "@/Components/Icons/LikeIcon.vue";
import ApiService from "@/Modules/core/infrastructure/ApiService";

export default {
    name: "CommodityRequestsManager",
    components: {
        CRCMDatatable,
        TopActionBtn,
        LikeIcon
    },
    computed: {
        CommodityRequest() {
            return CommodityRequest;
        },
        permissions() {
            return this.$page?.props?.permissions || [];
        },
        canView() {
            return this.permissions.includes('read-commodity-request');
        },
        canUpdate() {
            return this.permissions.includes('update-commodity-request');
        }
    },
    methods: {
        async updateStatus(id, status) {
            if (!confirm(`Are you sure you want to mark this request as ${status}?`)) return;
            
            const updateApi = new ApiService(route('api.commodity-requests.update', {id: id}));
            try {
                await updateApi.put({ status: status });
                await this.$refs.datatable?.dt?.refresh();
            } catch (error) {
                alert("Failed to update status.");
                console.error(error);
            }
        }
    }
}
</script>
