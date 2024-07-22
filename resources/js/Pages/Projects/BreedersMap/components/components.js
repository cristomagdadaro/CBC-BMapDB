import Breeder from "@/Pages/Projects/BreedersMap/domain/Breeder.js";
import Commodity from "@/Pages/Projects/BreedersMap/domain/Commodity.js";
import {defineAsyncComponent} from "vue";
import User from "@/Pages/Admin/domain/User.js";
import Account from "@/Pages/Admin/domain/Account.js";
import Application from "@/Pages/Admin/domain/Application.js";
/**
 * Contains the forms, pages, api routes, and models for the BreedersMap project
 **/
export const BreedersMapPages = {
    api: {
        user: {
            path: route('api.administrator.index'),
            name: 'Users Model',
            model: User,
        },
        account: {
            path: route('api.accounts.index'),
            name: 'Accounts Model',
            model: Account,
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
        breeder: {
            path: route('api.breeders.index'),
            name: 'Breeders Model',
            model: Breeder,
            create:{
                path: null,
                name: 'CreateBreederForm',
                component: defineAsyncComponent(
                    () => import('@/Pages/Projects/BreedersMap/presentation/components/breeders/CreateBreederForm.vue')
                ),
            },
            edit:{
                path: null,
                name: 'EditBreederForm',
                component: defineAsyncComponent(
                    () => import('@/Pages/Projects/BreedersMap/presentation/components/breeders/EditBreederForm.vue')
                ),
            },
            view: {
                path: 'breedersmap.breeder.view',
                name: 'ViewBreeder',
                component: defineAsyncComponent(
                    () => null,
                ),
            }
        },
        commodity: {
            path: route('api.commodities.index'),
            name: 'Commodities Model',
            model: Commodity,
            create:{
                path: null,
                name: 'CreateCommodityForm',
                component: defineAsyncComponent(
                    () => import('@/Pages/Projects/BreedersMap/presentation/components/commodity/CreateCommodityForm.vue')
                ),
            },
            edit:{
                path: null,
                name: 'EditCommodityForm',
                component: defineAsyncComponent(
                    () => import('@/Pages/Projects/BreedersMap/presentation/components/commodity/EditCommodityForm.vue')
                ),
            },
            view: {
                path: 'breedersmap.commodity.view',
                name: 'ViewCommodity',
                component: defineAsyncComponent(
                    () => null,
                ),
            },
            import: {
                path: 'breedersmap.commodity.import',
                name: 'ImportCommodities',
                component: defineAsyncComponent(
                    () => import('@/Pages/Projects/BreedersMap/presentation/components/commodity/ImportCommodities.vue')
                ),
            }
        },
    },
    index: {
        path: route('projects.breedersmap.index'),
        name: 'BreedersMapIndex',
        component: defineAsyncComponent(
            () => import('@/Pages/Projects/BreedersMap/presentation/BreedersMapIndex.vue')
        ),
    },
}
