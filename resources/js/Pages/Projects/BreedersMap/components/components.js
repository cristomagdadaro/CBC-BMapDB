import Breeder from "@/Pages/Projects/BreedersMap/domain/Breeder.js";
import CreateBreederForm from "@/Pages/Projects/BreedersMap/CreateBreederForm.vue";
import EditBreederForm from "@/Pages/Projects/BreedersMap/EditBreederForm.vue";
/**
 * Contains the forms, pages, api routes, and models for the BreedersMap project
 **/
export const BreedersMapPages = {
    api: {
        breeder: {
            path: route('api.breeders.index'),
            name: 'Breeders Model',
            model: Breeder,
        },
    },
    index: {
        path: route('projects.breedersmap.index'),
        name: 'BreedersMapIndex',
        component: () => import('@/Pages/Projects/BreedersMap/BreedersMapIndex.vue')
    },
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
}
