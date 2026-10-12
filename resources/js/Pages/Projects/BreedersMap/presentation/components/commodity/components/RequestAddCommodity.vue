<script>
import { Link } from "@inertiajs/vue3";
import {BaseButton} from "@/Components/CRCMDatatable/Components/index.js";
import DialogFormModal from "@/Components/CRCMDatatable/Layouts/DialogFormModal.vue";
import RequestAddCommodityForm
    from "@/Pages/Projects/BreedersMap/presentation/components/commodity/components/RequestAddCommodityForm.vue";
import ApiService from "@/Modules/core/infrastructure/ApiService";

export default {
    name: "RequestAddCommodity",
    components: {RequestAddCommodityForm, DialogFormModal, BaseButton, Link},
    data() {
        return {
            showAddCommodityForm: false,
        }
    },
    methods: {
        async submitRequest(form) {
            const api = new ApiService(route('api.commodity-requests.store'));
            try {
                const response = await api.post({
                    name: form.name,
                    scientific_name: form.scientific_name
                });
                alert("Your request to add the commodity has been submitted successfully!");
                this.showAddCommodityForm = false;
            } catch (err) {
                alert("An error occurred while submitting your request. Please try again.");
                console.error(err);
            }
        }
    }
}
</script>

<template>
    <div class="flex flex-col border-0 p-0 bg-transparent gap-1">
        <div class="flex justify-end items-center px-1 gap-2">
            <span class="text-gray-600 opacity-50 font-bold text-[0.6rem] text-right leading-none border border-gray-800 rounded-full p-0.5 px-1 hover:bg-cbc-dark-green hover:text-white hover:border-cbc-dark-green" title="Can't find your commodity?">
                ?
            </span>
        </div>
        <BaseButton @click.prevent="showAddCommodityForm = true" class="border-0 w-full text-gray-900 bg-cbc-yellow-green py-2 focus:ring-0 overflow-ellipsis leading-none text-xs">
            Request to Add Commodity
        </BaseButton>
    </div>
    <dialog-form-modal :show="showAddCommodityForm" @close="showAddCommodityForm = false">
        <request-add-commodity-form @submitForm="submitRequest" @close="showAddCommodityForm = false"/>
    </dialog-form-modal>
</template>

<style scoped>

</style>
