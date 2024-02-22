import Expert from "@/Pages/Projects/TWG/domain/Expert.js";
import Project from "@/Pages/Projects/TWG/domain/Project.js";
import Product from "@/Pages/Projects/TWG/domain/Product.js";
import Service from "@/Pages/Projects/TWG/domain/Service.js";

import { defineAsyncComponent } from "vue";
export const TWGPages = {
    api: {
        expert: {
            path: route('api.twg.experts.index'),
            name: 'Experts Model',
            model: Expert,
            create:{
                path: null,
                name: 'CreateExpertForm',
                component: defineAsyncComponent(
                    () => import('@/Pages/Projects/TWG/presentation/components/expert/CreateExpertForm.vue')
                ),
            },
            edit:{
                path: null,
                name: 'EditExpertForm',
                component: defineAsyncComponent(
                    () => import('@/Pages/Projects/TWG/presentation/components/expert/EditExpertForm.vue')
                ),
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
        component: defineAsyncComponent(
            () => import('@/Pages/Projects/TWG/presentation/TWGIndex.vue')
        ),
    },
    educLevelOptions: [
        {
            label: "Bachelor's",
            name: "Bachelor's",
        },
        {
            label: "Master's",
            name: "Master's",
        },
        {
            label: "Doctoral",
            name: "Doctoral",
        },
    ]
}
