<template>
    <div
        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700/60 p-6 transition-colors duration-200"
    >
        <h3
            class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center"
        >
            <i
                class="fas fa-bolt text-amber-500 dark:text-amber-400 mr-2.5 text-sm"
            ></i>
            Quick Actions
        </h3>

        <div class="grid grid-cols-2 gap-3">
            <button
                v-for="action in filteredActions"
                :key="action.label"
                @click="handleAction(action)"
                class="flex flex-col items-center justify-center p-3.5 border border-gray-200/80 dark:border-gray-700 rounded-xl hover:border-emerald-600 dark:hover:border-emerald-500 bg-white dark:bg-gray-800/50 hover:bg-emerald-50/40 dark:hover:bg-emerald-950/20 transition-all duration-200 group focus:outline-none focus:ring-2 focus:ring-emerald-500/50"
            >
                <div
                    class="w-10 h-10 rounded-lg flex items-center justify-center mb-2.5 shadow-sm group-hover:scale-105 transition-transform duration-200"
                    :class="action.bgColor"
                >
                    <i :class="action.icon" class="text-white text-base"></i>
                </div>
                <span
                    class="text-xs font-medium text-gray-700 dark:text-gray-300 group-hover:text-emerald-700 dark:group-hover:text-emerald-400 text-center transition-colors"
                >
                    {{ action.label }}
                </span>
            </button>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
    userRole: String,
});

const actions = [
    {
        label: "Manage Users",
        icon: "fas fa-users-cog",
        bgColor: "bg-red-500 dark:bg-red-600",
        route: "/administrator/users",
        roles: ["administrator"],
    },
    {
        label: "System Settings",
        icon: "fas fa-cog",
        bgColor: "bg-gray-600 dark:bg-gray-500",
        route: "/administrator/settings",
        roles: ["administrator"],
    },
    {
        label: "View Profile",
        icon: "fas fa-user",
        bgColor: "bg-blue-500 dark:bg-blue-600",
        route: "/user/profile",
        roles: ["administrator", "breeder", "focal person", "researcher"],
    },
    {
        label: "Breeders Map",
        icon: "fas fa-map-marked-alt",
        bgColor: "bg-emerald-500 dark:bg-emerald-600",
        route: "/projects/breedersmap",
        roles: ["administrator", "breeder", "focal person", "researcher"],
    },
    {
        label: "TWG Database",
        icon: "fas fa-database",
        bgColor: "bg-indigo-500 dark:bg-indigo-600",
        route: "/projects/twgdb",
        roles: ["administrator", "breeder", "focal person", "researcher"],
    },
    {
        label: "Security",
        icon: "fas fa-shield-alt",
        bgColor: "bg-purple-500 dark:bg-purple-600",
        route: "/user/profile",
        roles: ["administrator", "breeder", "focal person", "researcher"],
    },
];

const filteredActions = computed(() => {
    return actions.filter((action) =>
        action.roles.includes(props.userRole?.toLowerCase()),
    );
});

const handleAction = (action) => {
    if (action.route) {
        router.visit(action.route);
    }
};
</script>
