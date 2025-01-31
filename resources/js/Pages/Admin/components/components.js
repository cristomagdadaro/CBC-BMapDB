import {defineAsyncComponent} from "vue";
import User from "@/Pages/Admin/domain/User";
import Account from "@/Pages/Admin/domain/Account";
import Application from "@/Pages/Admin/domain/Application";
import Role from "../domain/Role";
/**
 * Contains the forms, pages, api routes, and models for the BreedersMap project
 **/
export const AdminPages = {
    api: {
        user: {
            path: route('api.administrator.index'),
            name: 'Users Model',
            model: User,
            create: {
                path: null,
                name: 'CreateUserForm',
                component: defineAsyncComponent(
                    () => import('@/Pages/Admin/components/NewUser/CreateUserForm.vue')
                ),
            },
            edit: {
                path: null,
                name: 'EditUserForm',
                component: defineAsyncComponent(
                    () => import('@/Pages/Admin/components/NewUser/EditUserForm.vue')
                ),
            },
            view: {
                path: 'administrator.user.view',
                name: 'ViewUser',
                component: defineAsyncComponent(
                    () => null,
                ),
            }
        },
        account: {
            path: route('api.accounts.index'),
            name: 'Accounts Model',
            model: Account,
            create: {
                path: null,
                name: 'CreateUserForm',
                component: defineAsyncComponent(
                    () => import('@/Pages/Admin/components/Accounts/CreateAccountForm.vue')
                ),
            },
            edit: {
                path: null,
                name: 'EditUserForm',
                component: defineAsyncComponent(
                    () => import('@/Pages/Admin/components/Accounts/EditAccountForm.vue')
                ),
            },
            view: {
                path: null,
                name: 'ViewUser',
                component: defineAsyncComponent(
                    () => null,
                ),
            }
        },
        app: {
            path: route('api.applications.index'),
            name: 'Application Model',
            model: Application,
            create: {
                path: null,
                name: 'CreateUserForm',
                component: defineAsyncComponent(
                    () => import('@/Pages/Admin/components/Applications/CreateApplicationForm.vue')
                ),
            },
            edit: {
                path: null,
                name: 'EditUserForm',
                component: defineAsyncComponent(
                    () => import('@/Pages/Admin/components/Applications/EditApplicationForm.vue')
                ),
            },
            view: {
                path: null,
                name: 'ViewApplication',
                component: defineAsyncComponent(
                    () => null,
                ),
            }
        },
        role: {
            path: route('api.roles.index'),
            name: 'Role Model',
            model: Role,
            create: {
                path: null,
                name: 'CreateRoleForm',
                component: defineAsyncComponent(
                    () => import('@/Pages/Admin/components/Roles/CreateRoleForm.vue')
                ),
            },
            edit: {
                path: null,
                name: 'EditRoleForm',
                component: defineAsyncComponent(
                    () => import('@/Pages/Admin/components/Roles/EditRoleForm.vue')
                ),
            },
            view: {
                path: null,
                name: 'ViewRole',
                component: defineAsyncComponent(
                    () => null,
                ),
            }
        }
    },
    index: {
        path: route('administrator.index'),
        name: 'AdministratorIndex',
        component: defineAsyncComponent(
            () => import('@/Pages/Admin/Administrator.vue')
        ),
    },
}
