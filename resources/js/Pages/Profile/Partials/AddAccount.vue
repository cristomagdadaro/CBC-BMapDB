<script>
import ActionSection from "@/Components/ActionSection.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import Modal from "@/Components/Modal.vue";
import Dropdown from "@/Components/Dropdown.vue";
import DropdownOption from "@/Components/CustomDropdown/Components/DropdownOption.vue";
import CustomDropdown from "@/Components/CustomDropdown/CustomDropdown.vue";
import ApiService from "@/Modules/core/infrastructure/ApiService";
import DtoError from "@/Modules/core/dto/base/DtoError";
import SelectField from "@/Components/Form/SelectField.vue";
import RequestNewAccessMixin from "@/Pages/mixins/RequestNewAccessMixin";
import {useForm} from "@inertiajs/vue3";
import LoaderIcon from "@/Components/Icons/LoaderIcon.vue";

export default {
    name: "AddAccount",
    components: {LoaderIcon, SelectField, CustomDropdown, DropdownOption, Dropdown, Modal, PrimaryButton, ActionSection},
    mixins: [RequestNewAccessMixin],
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
            form: null,
            apiService: null,
            errors: null
        }
    },
    methods: {
        async submitForm() {
            this.form.post(route('api.accounts.store'), {
                onFinish: (response) => function (){
                    if (response instanceof this.DtoError){
                        this.errors = response;
                        new Notification(response);
                    }else
                        this.showAddAccountModal = false;
                },
            });
        },
    },
    watch: {
        showAddAccountModal(val) {
            if (!val) {
                this.form.app_id = null;
            }
        },
        selectedApplication(newVal) {
            this.form.app_id = newVal;
            this.filterRolesByApplication();
        }
    },
    beforeMount() {
        this.form = useForm({
            app_id: null,
            user_id: this.$page.props.auth.user.id,
            role: null,
        })
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
                You have access the to following applications: <span v-if="!$page.props?.accountsPending.length && !$page.props.auth.user.accounts.length" class="py-2 bg-white text-sm font-medium text-red-600"> Doesn't have any database access, kindly make a request</span>
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
                    <div class="flex justify-between text-sm">
                        <label for="database" class="block font-medium text-gray-700">Request new access to</label>
                        <span v-if="errors" class="text-red-700">{{ errors.message }}</span>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <SelectField v-if="applications" id="app_id" label="Database" v-model="selectedApplication" type="text" required autofocus autocomplete="name" :error="form?.errors.app_id" :options="applications" />
                        <SelectField v-if="roles" id="role" label="Access Level" :disabled="!selectedApplication" v-model="form.role" type="text" required autofocus autocomplete="role" :error="form?.errors.role" :options="filteredRoles" />
                    </div>
                    <PrimaryButton type="submit" :disabled="form.processing" :class="{ 'opacity-50': form.processing }">
                        Submit Request
                        <loader-icon v-show="form.processing" />
                    </PrimaryButton>
                </form>
            </modal>
        </template>
    </ActionSection>
</template>

<style scoped>

</style>
