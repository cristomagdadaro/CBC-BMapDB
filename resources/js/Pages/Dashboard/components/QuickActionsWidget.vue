<template>
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-bolt text-yellow-500 mr-2"></i>
            Quick Actions
        </h3>

        <div class="grid grid-cols-2 gap-3">
            <button
                v-for="action in filteredActions"
                :key="action.label"
                @click="handleAction(action)"
                class="flex flex-col items-center p-4 border-2 border-gray-200 rounded-lg hover:border-cbc-dark-green hover:bg-gray-50 transition group"
            >
                <div
                    class="w-12 h-12 rounded-full flex items-center justify-center mb-2 group-hover:scale-110 transition"
                    :class="action.bgColor"
                >
                    <i :class="action.icon" class="text-white text-xl"></i>
                </div>
                <span class="text-sm font-medium text-gray-700">{{ action.label }}</span>
            </button>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { router } from '@inertiajs/vue3';

const props = defineProps({
    userRole: String
});

const actions = [
    {
        label: 'Manage Users',
        icon: 'fas fa-users-cog',
        bgColor: 'bg-red-500',
        route: '/administrator/users',
        roles: ['admin']
    },
    {
        label: 'System Settings',
        icon: 'fas fa-cog',
        bgColor: 'bg-gray-600',
        route: '/administrator/settings',
        roles: ['admin']
    },
    {
        label: 'View Profile',
        icon: 'fas fa-user',
        bgColor: 'bg-blue-500',
        route: '/user/profile',
        roles: ['admin', 'breeder', 'focal person', 'researcher']
    },
    {
        label: 'Breeders Map',
        icon: 'fas fa-map-marked-alt',
        bgColor: 'bg-green-500',
        route: '/projects/breedersmap',
        roles: ['admin', 'breeder', 'focal person', 'researcher']
    },
    {
        label: 'TWG Database',
        icon: 'fas fa-database',
        bgColor: 'bg-indigo-500',
        route: '/projects/twgdb',
        roles: ['admin', 'breeder', 'focal person', 'researcher']
    },
    {
        label: 'Security',
        icon: 'fas fa-shield-alt',
        bgColor: 'bg-purple-500',
        route: '/user/profile',
        roles: ['admin', 'breeder', 'focal person', 'researcher']
    }
];

const filteredActions = computed(() => {
    return actions.filter(action =>
        action.roles.includes(props.userRole?.toLowerCase())
    );
});

const handleAction = (action) => {
    if (action.route) {
        router.visit(action.route);
    }
};
</script>
