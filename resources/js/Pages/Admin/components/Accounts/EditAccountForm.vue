<script>

import FormMixin from "@/Pages/mixins/FormMixin";
import BaseButton from "@/Components/CRCMDatatable/Components/BaseButton.vue";
import ApiService from "@/Modules/core/infrastructure/ApiService";
import BaseRequest from "@/Modules/core/domain/base/BaseRequest";
import Role from "@/Modules/core/domain/auth/Role";
import CustomDropdown from "@/Components/CustomDropdown/CustomDropdown.vue";
import SelectSearchField from "@/Components/Form/SelectSearchField.vue";
import BaseEditForm from "@/Components/Modal/BaseEditForm.vue";
import AuthAccount from "@/Pages/Admin/domain/Account";
import User from "../../../../Modules/core/domain/auth/User";

export default {
    components: {BaseEditForm, SelectSearchField, CustomDropdown, BaseButton},
    mixins: [FormMixin],
    name: "EditAccountForm",
    data() {
        return {
            model: AuthAccount,
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
        checkBoxPermissionChange(event, permissionId) {
            // Ensure `this.form.permissions` is initialized as an array
            if (!this.form.permissions) {
                this.form.permissions = [];
            }

            if (event.target.checked) {
                // Add `permissionId` if not already present
                if (!this.form.permissions.includes(permissionId)) {
                    this.form.permissions.push(permissionId);
                }

                // Remove the negative counterpart if it exists
                this.form.permissions = this.form.permissions.filter(permission => permission !== -permissionId);

                // If `permissionId` exists in `permissionsList`, remove it
                if (this.checkUserPermission(permissionId)) {
                    this.form.permissions = this.form.permissions.filter(permission => permission !== permissionId);
                }
            } else {
                if (this.permissionsList.includes(permissionId) && !this.form.permissions.includes(-permissionId)) {
                    this.form.permissions.push(-permissionId);
                }

                this.form.permissions = this.form.permissions.filter(permission => permission !== permissionId);
            }
        },
        checkBoxRoleChange(event, roleId) {
            // Ensure `this.form.role` is initialized as an array
            if (!this.form.role) {
                this.form.role = [];
            }

            if (event.target.checked) {
                // Add `roleId` if not already present (positive value)
                if (!this.form.role.includes(roleId)) {
                    this.form.role.push(roleId);
                }

                // Remove the negative counterpart if it exists
                this.form.role = this.form.role.filter(role => role !== -roleId);

                // If `roleId` exists in `roleIdList`, remove it
                if (this.roleIdList.includes(roleId)) {
                    this.form.role = this.form.role.filter(role => role !== roleId);
                }
            } else {
                // If `roleId` exists in `roleIdList`, add `-roleId` (negative value)
                if (this.roleIdList.includes(roleId) && !this.form.role.includes(-roleId)) {
                    this.form.role.push(-roleId);
                }

                // Remove `roleId` (positive) if it exists
                this.form.role = this.form.role.filter(role => role !== roleId);
            }
        },
        checkPermission(permissionId) {
            return this.permissionsList.includes(permissionId);
        },
        checkUserPermission(permissionId) {
            return this.form.user.userPermissionsList.map(permission => permission.id).includes(permissionId);
        },
        checkRole(roleId) {
            return this.roleIdList.includes(roleId);
        }
    },
    computed: {
        User() {
            return User
        },
        selectedRole() {
            return this.roles.find(role => role.id === this.form.role);
        },
        roleIdList() {
            return this.form.user.roleList.map(role => role.id);
        },
        permissionsList() {
            // Combine userPermissionsList and rolePermissionsList into one array and map to permission IDs
            return [...this.form.user.userPermissionsList, ...this.form.user.rolePermissionsList]
                .map(permission => permission.id);
        }
    },
    beforeMount() {
        this.getPermissions();
        this.getRoles();
    }
}
</script>

<template>
    <base-edit-form :form="form" v-if="form.user" :forceClose="forceClose" @resetForm="resetForm">
        <template v-slot:formTitle>
            Approve User Account
        </template>
        <template v-slot:formFields>
            <!--      Temporary delete, this allow user to assigned new user to an account or new account to a user          -->
            <!--   <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">

             <select-search-field :api-link="route('api.users.index')" :error="getError('user_id')" label="User" v-model="form.user_id" required />
                <select-search-field :api-link="route('api.applications.index')" :error="getError('app_id')" label="App" v-model="form.app_id" required />
            </div>-->

<!--            <div v-if="!form.approved_at" class="p-2 my-2 rounded-md">
                Take this action with caution. This will approve the user account and grant access to the application.
            </div>
            <div v-else class="p-2 my-2 rounded-md">
                Take this action with caution. This will remove the user's access to the application.
            </div>-->
            <div class="flex flex-col gap-2">
                <div class="flex flex-col items-center bg-cbc-dark-green p-1 py-2 rounded text-gray-100">
                    <span class="font-semibold text-lg">
                        {{ (new User(form.user)).getFullName }}
                    </span>
                    <div class="text-sm text-gray-400 flex flex-wrap gap-1">
                        <label class="border-gray-400 border-r px-1">{{ form.user.id }}</label>
                        <label>{{ form.user.email }}</label>
                    </div>
                </div>
                <div class="flex justify-between">
                    <span>
                        <span v-if="form.created_at">Requested at: {{ form.created_at }}</span>
                    </span>
                    <span>
                        <span v-if="form.approved_at">Approved at: {{ form.approved_at }}</span>
                        <span v-else>Not yet approved</span>
                    </span>
                </div>
                <base-button v-if="!form.approved_at" @click.prevent="dateNow" class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded items-center flex justify-center">Approve Now</base-button>
                <base-button v-else @click.prevent="removeDateNow" class="bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded items-center flex justify-center">Remove Access</base-button>
            </div>
            <div>
                <p class="mt-2">
                    <strong>Assign Permissions via Role:</strong> The user will inherit the default permissions a role assigned to them
                </p>
                <div class="flex flex-col gap-1">
                    <template v-for="role in roles">
                        <ul class="flex items-center gap-1 m-1 p-2 rounded bg-gray-200">
                            <input type="checkbox" :checked="checkRole(role.id)" :value="role.id" @change="checkBoxRoleChange($event, role.id)" class="rounded-full" />
                            <div class="flex flex-col w-full">
                                <span>{{ role.name }}</span>
                                <div class="grid sm:grid-cols-3 grid-cols-2 gap-1 border border-t-gray-400">
                                    <span v-for="permission in role.permissions" :key="permission.id" class="text-sm text-gray-600 whitespace-nowrap">• {{ permission.name }}</span>
                                </div>
                            </div>
                        </ul>
                    </template>
                </div>

            </div>
            <div>
                <p class="mt-2">
                    <strong>Assign Custom Permissions:</strong> The user will have these permissions in addition to the permissions of the role assigned to them
                </p>
                <div class="flex flex-col gap-1">
                    <template v-for="action in permissions">
                        <ul class="grid sm:grid-cols-3 grid-cols-2 m-1 p-2 rounded bg-gray-200">
                            <li v-for="permission in action" :key="permission.id" class="flex items-center gap-1 select-none" >
                                <input type="checkbox" :disabled="!checkUserPermission(permission.id) && checkPermission(permission.id)" :checked="checkPermission(permission.id)" :value="permission.id" @change="checkBoxPermissionChange($event, permission.id)" class="rounded-full disabled:opacity-25 disabled:cursor-not-allowed" />
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
