import { createRouter, createWebHistory } from 'vue-router';

const routes = [
    {
        path: '/',
        component: async () => await import('@/Pages/Projects.vue'),
        name: 'Home',
    },
    {
        path: '/register',
        component: async () => await import('@/Pages/Auth/Register.vue'),
        name: 'Register',
    },
    {
        path: '/login',
        component: async () => await import('@/Pages/Auth/Login.vue'),
        name: 'Login',
    },
    {
        path: '/forgot-password',
        component: async () => await import('@/Pages/Auth/ForgotPassword.vue'),
        name: 'Forgot Password',
    },
    {
        path: '/verification',
        component: async () => await import('@/Pages/Auth/Register.vue'),
        name: 'Forgot Password',
    },
    {
        path: '/administrator',
        component: async () => await import('@/Pages/Admin/Administrator.vue'),
        name: 'administrator.index',
        children: [
            {
                path: '/administrator/unverified-users',
                component: async () => await import('@/Pages/Admin/components/NewUser/NewUserTable.vue'),
                name: 'administrator.unverified-users',
            },
            {
                path: '/administrator/approved-accounts',
                component: async () => await import('@/Pages/Admin/components/Accounts/AppAccountsTable.vue'),
                name: 'administrator.approved-accounts',
            },
            {
                path: '/administrator/applications',
                component: async () => await import('@/Pages/Admin/components/Applications/ApplicationsTable.vue'),
                name: 'administrator.applications',
            },
        ]
    },
    {
        path: '/projects',
        component: async () => await import('@/Pages/Projects.vue'),
        name: 'Projects',
        children: [
            {
                path: '/projects/breedersmap',
                component: async () => await import('@/Pages/Projects/BreedersMap/presentation/components/breeders/BreedersTable.vue'),
                name: 'projects.breedersmap.index',
                children: [
                    {
                        path: '/projects/breedersmap/breeder',
                        component: async () => await import('@/Pages/Projects/BreedersMap/presentation/components/breeders/BreedersTable.vue'),
                        name: 'projects.breedersmap.breeder',
                        children: [
                            {
                                path: '/projects/breedersmap/breeder/:id',
                                component: async () => await import('@/Pages/Projects/BreedersMap/presentation/BreedersMapViewBreeder.vue'),
                                name: 'breedersmap.breeder.view',
                            }
                        ]
                    },
                    {
                        path: '/projects/breedersmap/commodity',
                        component: async () => await import('@/Pages/Projects/BreedersMap/presentation/components/commodity/CommodityTable.vue'),
                        name: 'projects.breedersmap.commodity',
                        children: [
                            {
                                path: '/projects/breedersmap/commodity/:id',
                                component: async () => await import('@/Pages/Projects/BreedersMap/presentation/BreedersMapViewCommodity.vue'),
                                name: 'breedersmap.commodity.view',
                            }
                        ]
                    },
                    {
                        path: '/projects/breedersmap/geomap',
                        component: async () => await import('@/Pages/Projects/BreedersMap/presentation/components/map/Map.vue'),
                        name: 'projects.breedersmap.geomap',
                    },
                    {
                        path: '',
                        redirect: { name: 'projects.breedersmap.breeder' },
                        name: 'projects.breedersmap',
                    },
                ]
            },
            {
                path: '/projects/twgdb',
                component: async () => await import('@/Pages/Projects/TWG/presentation/components/summary/TWGSummary.vue'),
                name: 'projects.twg.summary',
                children: [
                    {
                        path: '/projects/twgdb/expert',
                        component: async () => await import('@/Pages/Projects/TWG/presentation/components/expert/ExpertsTable.vue'),
                        name: 'projects.twg.experts',
                    },
                    {
                        path: '/projects/twgdb/product',
                        component: async () => await import('@/Pages/Projects/TWG/presentation/components/product/ProductsTable.vue'),
                        name: 'projects.twg.products',
                    },
                    {
                        path: '/projects/twgdb/project',
                        component: async () => await import('@/Pages/Projects/TWG/presentation/components/project/ProjectsTable.vue'),
                        name: 'projects.twg.projects',
                    },
                    {
                        path: '/projects/twgdb/service',
                        component: async () => await import('@/Pages/Projects/TWG/presentation/components/service/ServicesTable.vue'),
                        name: 'projects.twg.services',
                    },

                ]
            }
        ]
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
