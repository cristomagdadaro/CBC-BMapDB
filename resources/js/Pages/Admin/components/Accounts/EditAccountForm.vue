<script>

import FormMixin from "@/Pages/mixins/FormMixin";
import BaseButton from "@/Components/CRCMDatatable/Components/BaseButton.vue";
import ApiService from "@/Modules/core/infrastructure/ApiService";
import Permission from "@/Modules/core/domain/auth/Permission";
import BaseRequest from "@/Modules/core/domain/base/BaseRequest";
import Role from "@/Modules/core/domain/auth/Role";
import CustomDropdown from "@/Components/CustomDropdown/CustomDropdown.vue";
import User from "@/Modules/core/domain/auth/User";
import SelectSearchField from "@/Components/Form/SelectSearchField.vue";
import BaseEditForm from "@/Components/Modal/BaseEditForm.vue";

export default {
    components: {BaseEditForm, SelectSearchField, CustomDropdown, BaseButton},
    mixins: [FormMixin],
    name: "EditAccountForm",
    data() {
        return {
            form: {
                user_id: null,
                app_id: null,
                approved_at: null,
                permissions: [],
                role: null
            },
            permissions: [],
            roles: []
        };
    },
    methods: {
        dateNow() {
            this.form.approved_at = new Date().toISOString().slice(0, 16).replace('T', ' ');
        },
        removeDateNow() {
            this.form.approved_at = null;
        },
        getPermissions() {
            const service = new ApiService(route('api.permissions.index'));
            service.get(new BaseRequest()).then(response => {
                this.permissions = response.data;
            });
        },
        getRoles() {
            const service = new ApiService(route('api.roles.index'));
            service.get(new BaseRequest(), Role).then(response => {
                this.roles = response.data;
            });
        },
        checkBoxChange(event, permissionId) {
            if (event.target.checked) {
                // check if form has permissions
                if (!this.form.permissions) {
                    this.form.permissions = [];
                }

                this.form.permissions.push(permissionId);
            } else {
                this.form.permissions = this.form.permissions.filter(permission => permission !== permissionId);
            }
        },
    },
    computed: {
        selectedRole() {
            return this.roles.find(role => role.id === this.form.role);
        },
    },
    beforeMount() {
        this.getPermissions();
        this.getRoles();
    }
}
</script>

<template>
    <base-edit-form :form="form" :forceClose="forceClose" @resetForm="resetForm">
        <template v-slot:formTitle>
            Approve User Account
        </template>
        <template v-slot:formFields>
            <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                <select-search-field :api-link="route('api.users.index')" :error="getError('user_id')" label="User" v-model="form.user_id" required />
                <select-search-field :api-link="route('api.applications.index')" :error="getError('app_id')" label="App" v-model="form.app_id" required />
            </div>
            <div v-if="!form.approved_at" class="p-2 my-2 rounded-md">
                Take this action with caution. This will approve the user account and grant access to the application.
            </div>
            <div v-else class="p-2 my-2 rounded-md">
                Take this action with caution. This will remove the user's access to the application.
            </div>
            <div class="flex flex-col gap-2">
                <span>
                    <span v-if="form.approved_at">Approved at: {{ form.approved_at }}</span>
                    <span v-else>Not yet approved</span>
                </span>
                <base-button v-if="!form.approved_at" @click.prevent="dateNow" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded items-center flex justify-center">Approve Now</base-button>
                <base-button v-else @click.prevent="removeDateNow" class="bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded items-center flex justify-center">Remove Access</base-button>
            </div>
            <div>
                <p class="mt-2">
                    Assign Permission by role: The user will inherit the permissions of the role assigned to them
                </p>
                <div class="flex flex-col gap-1">
                    <div class="m-1 p-2 rounded bg-gray-200">
                        <custom-dropdown :options="roles.map(
                            role => ({name: role.id, label: role.name})
                        )" v-model="form.permissions" placeholder="Assign a Role" :with-all-option="false" :value="form.role" @selectedChange="form.role = $event" />
                        <ul v-if="form.role" class="grid grid-cols-2 m-1 p-2 rounded bg-gray-200">
                            <li v-for="permission in selectedRole" :key="permission.id" class="flex items-center gap-1 select-none" >
                                {{ permission.name }}
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
            <div>
                <p class="mt-2">
                    Assign Custom Permissions: The user will have these permissions in addition to the permissions of the role assigned to them
                </p>
                <div class="flex flex-col gap-1">
                    <template v-for="action in permissions">
                        <ul class="grid grid-cols-2 m-1 p-2 rounded bg-gray-200">
                            <li v-for="permission in action" :key="permission.id" class="flex items-center gap-1 select-none" >
                                <input type="checkbox" :value="permission.id" @change="checkBoxChange($event, permission.id)" class="rounded-full" />
                                {{ permission.name }}
                            </li>
                        </ul>
                    </template>
                </div>

            </div>
        </template>
    </base-edit-form>
</template>

<style scoped>

</style>
