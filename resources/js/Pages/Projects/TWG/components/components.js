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
                component: defineAsyncComponent(
                    () => import('@/Pages/Projects/TWG/presentation/components/project/CreateProjectForm.vue')

                ),
            },
            edit:{
                path: null,
                name: 'EditProjectForm',
                component: defineAsyncComponent(
                    () => import('@/Pages/Projects/TWG/presentation/components/project/EditProjectForm.vue')
                ),
            }
        },
        product: {
            path: route('api.twg.products.index'),
            name: 'Products Model',
            model: Product,
            create:{
                path: null,
                name: 'CreateProductForm',
                component: defineAsyncComponent(
                    () => import('@/Pages/Projects/TWG/presentation/components/product/CreateProductForm.vue')
                ),
            },
            edit:{
                path: null,
                name: 'EditProductForm',
                component: defineAsyncComponent(
                    () => import('@/Pages/Projects/TWG/presentation/components/product/EditProductForm.vue')
                ),
            }
        },
        service: {
            path: route('api.twg.services.index'),
            name: 'Services Model',
            model: Service,
            create:{
                path: null,
                name: 'CreateServiceForm',
                component: defineAsyncComponent(
                    () => import('@/Pages/Projects/TWG/presentation/components/service/CreateServiceForm.vue')
                ),
            },
            edit:{
                path: null,
                name: 'EditServiceForm',
                component: defineAsyncComponent(
                    () => import('@/Pages/Projects/TWG/presentation/components/service/EditServiceForm.vue')
                ),
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
