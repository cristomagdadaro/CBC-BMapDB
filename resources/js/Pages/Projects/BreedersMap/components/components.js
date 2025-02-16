import Breeder from "@/Pages/Projects/BreedersMap/domain/Breeder";
import Commodity from "@/Pages/Projects/BreedersMap/domain/Commodity";
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
            },
            import: {
                path: 'breedersmap.breeder.import',
                name: 'ImportBreedersForm',
                component: defineAsyncComponent(
                    () => import('@/Pages/Projects/BreedersMap/presentation/components/breeders/ImportBreedersForm.vue')
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
