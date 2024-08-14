import { createRouter, createWebHistory } from 'vue-router';

const routes = [
    {
        path: '/projects/breedersmap',
        component: async () => await import('@/Pages/Projects/BreedersMap/presentation/components/breeders/BreedersTable.vue'),
        name: 'BreederTables',
    },
    {
        path: '/projects/breedersmap/breeder',
        component: async () => await import('@/Pages/Projects/BreedersMap/presentation/components/breeders/BreedersTable.vue'),
        name: 'BreederTable',
    },
    {
        path: '/projects/breedersmap/commodity',
        component: async () => await import('@/Pages/Projects/BreedersMap/presentation/components/commodity/CommodityTable.vue'),
        name: 'CommodityTable',
    },
    {
        path: '/projects/breedersmap/geomap',
        component: async () => await import('@/Pages/Projects/BreedersMap/presentation/components/map/Map.vue'),
        name: 'GeoMap',
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
