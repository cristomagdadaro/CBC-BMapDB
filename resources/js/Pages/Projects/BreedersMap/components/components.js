import Breeder from "@/Pages/Projects/BreedersMap/domain/Breeder.js";
import Commodity from "@/Pages/Projects/BreedersMap/domain/Commodity.js";
import {defineAsyncComponent} from "vue";
/**
 * Contains the forms, pages, api routes, and models for the BreedersMap project
 **/
export const BreedersMapPages = {
    api: {
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
