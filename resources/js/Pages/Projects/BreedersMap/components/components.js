import Breeder from "@/Pages/Projects/BreedersMap/domain/Breeder.js";
import CreateBreederForm from "@/Pages/Projects/BreedersMap/presentation/components/breeders/CreateBreederForm.vue";
import EditBreederForm from "@/Pages/Projects/BreedersMap/presentation/components/breeders/EditBreederForm.vue";
import Commodity from "@/Pages/Projects/BreedersMap/domain/Commodity.js";
import CreateCommodityForm
    from "@/Pages/Projects/BreedersMap/presentation/components/commodity/CreateCommodityForm.vue";
import EditCommodityForm from "@/Pages/Projects/BreedersMap/presentation/components/commodity/EditCommodityForm.vue";
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
                component: CreateBreederForm,
            },
            edit:{
                path: null,
                name: 'EditBreederForm',
                component: EditBreederForm,
            }
        },
        commodity: {
            path: route('api.commodities.index'),
            name: 'Commodities Model',
            model: Commodity,
            create:{
                path: null,
                name: 'CreateCommodityForm',
                component: CreateCommodityForm,
            },
            edit:{
                path: null,
                name: 'EditCommodityForm',
                component: EditCommodityForm,
            }
        },
    },
    index: {
        path: route('projects.breedersmap.index'),
        name: 'BreedersMapIndex',
        component: () => import('@/Pages/Projects/BreedersMap/presentation/BreedersMapIndex.vue')
    },
}

