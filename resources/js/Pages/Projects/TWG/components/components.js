import Expert from "@/Pages/Projects/TWG/domain/Expert.js";
import CreateBreederForm from "@/Pages/Projects/BreedersMap/presentation/components/breeders/CreateBreederForm.vue";
import TWGFormProject from "@/Pages/Projects/TWG/presentation/components/project/TWGFormProject.vue";
import Project from "@/Pages/Projects/TWG/domain/Project.js";
import Product from "@/Pages/Projects/TWG/domain/Product.js";
import Service from "@/Pages/Projects/TWG/domain/Service.js";
import CreateExpertForm from "@/Pages/Projects/TWG/presentation/components/expert/CreateExpertForm.vue";
export const TWGPages = {
    api: {
        expert: {
            path: route('api.twg.experts.index'),
            name: 'Experts Model',
            model: Expert,
            create:{
                path: null,
                name: 'CreateExpertForm',
                component: CreateExpertForm,
            },
            edit:{
                path: null,
                name: 'EditExpertForm',
                component: CreateExpertForm,
            }
        },
        project: {
            path: route('api.twg.projects.index'),
            name: 'Projects Model',
            model: Project,
            create:{
                path: null,
                name: 'CreateProjectForm',
                component: null,
            },
            edit:{
                path: null,
                name: 'EditProjectForm',
                component: null,
            }
        },
        product: {
            path: route('api.twg.products.index'),
            name: 'Products Model',
            model: Product,
            create:{
                path: null,
                name: 'CreateProductForm',
                component: null,
            },
            edit:{
                path: null,
                name: 'EditProductForm',
                component: null,
            }
        },
        service: {
            path: route('api.twg.services.index'),
            name: 'Services Model',
            model: Service,
            create:{
                path: null,
                name: 'CreateServiceForm',
                component: null,
            },
            edit:{
                path: null,
                name: 'EditServiceForm',
                component: null,
            }
        },
    },
    index: {
        path: route('projects.twg.index'),
        name: 'TWGIndex',
        component: () => import('@/Pages/Projects/TWG/presentation/TWGIndex.vue')
    },
}
