<script>
import ActionSection from "@/Components/ActionSection.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Modal from "@/Components/Modal.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownOption from "@/Components/CustomDropdown/Components/DropdownOption.vue";
import CustomDropdown from "@/Components/CustomDropdown/CustomDropdown.vue";
import ApiService from "@/Modules/core/infrastructure/ApiService";
import DtoError from "@/Modules/core/dto/base/DtoError";

export default {
    name: "AddAccount",
    components: {CustomDropdown, DropdownOption, Dropdown, Modal, PrimaryButton, ActionSection},
    props: {
        accountsPending: Object,
    },
    computed: {
        DtoError() {
            return DtoError;
        }
    },
    data(){
        return {
            showAddAccountModal: false,
            form: {
                app_id: null,
                user_id: this.$page.props.auth.user.id,
            },
            apiService: null,
            errors: null
        }
    },
    methods: {
        async submitForm() {
            this.apiService = new ApiService(route('api.accounts.store'));
            const response = await this.apiService.post(this.form);
            if (response instanceof this.DtoError){
                this.errors = response;
                new Notification(response);
            }else
                this.showAddAccountModal = false;
        },
        onSelectedChanged(value) {
            this.form.app_id = value;
        }
    },
    watch: {
        showAddAccountModal(val) {
            if (!val) {
                this.form.app_id = null;
            }
        }
    }
}
</script>

<template>
    <ActionSection>
        <template #title>
            Registered Accounts
        </template>
        <template #description>
            List of all your registered accounts. You can add more accounts by clicking the button below.
        </template>
        <template #content>
            <h3 class="text-lg font-medium text-gray-900">
                You have access the to following applications:
            </h3>
            <ul class="max-w-xl text-sm text-gray-600">
                <li v-if="$page.props.auth.user.accounts.length" class="py-2 bg-white font-bold text-xs text-gray-700 uppercase tracking-widest transition">Approved</li>
                <li v-for="account in $page.props.auth.user.accounts" class="px-4 bg-white font-semibold text-xs text-gray-700 tracking-widest transition">
                    {{ account.application.name }}
                </li>
                <li v-if="$page.props.accountsPending && $page.props.accountsPending.length" class="py-2 bg-white font-bold text-xs text-gray-700 uppercase tracking-widest transition">Pending</li>
                <li v-for="account in $page.props.accountsPending" class="px-4 bg-white font-semibold text-xs text-gray-700 tracking-widest transition">
                    {{ account.application.name }}
                </li>
            </ul>

            <div class="mt-5">
                <PrimaryButton type="button" @click="showAddAccountModal = true">
                    Request Access
                </PrimaryButton>
            </div>
            <modal :show="showAddAccountModal" @close="showAddAccountModal = false"
                   :closeable="true"
            >
                <form @submit.prevent="submitForm" class="p-5 px-10 flex flex-col gap-5">
                    <div class="mt-5">
                        <div class="flex justify-between text-sm">
                            <label for="database" class="block font-medium text-gray-700">Application <span class="text-red-700">*</span></label>
                            <span v-if="errors" class="text-red-700">{{ errors.message }}</span>
                        </div>
                        <custom-dropdown
                            placeholder="Select an application"
                            :value="form.app_id"
                            @selectedChange="onSelectedChanged"
                            :withAllOption="false"
                            :options="[
                                {
                                    name: 1,
                                    label: 'Biotech TWG Database'
                                },
                                {
                                    name: 2,
                                    label:'Plant Breeders Map',
                                }
                            ]"
                        />
                    </div>
                    <PrimaryButton type="submit">
                        Submit Request
                    </PrimaryButton>
                </form>
            </modal>
        </template>
    </ActionSection>
</template>

<style scoped>

</style>
