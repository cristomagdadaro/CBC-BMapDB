<template>
    <div
        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700/60 p-6 transition-colors duration-200"
    >
        <!-- Header -->
        <h3
            class="text-base font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center"
        >
            <i
                class="fas fa-chart-line text-indigo-500 dark:text-indigo-400 mr-2 text-sm"
            ></i>
            System Overview
        </h3>

        <!-- Stat Cards Grid -->
        <div class="grid grid-cols-2 gap-3 mb-6">
            <div
                class="p-3.5 bg-blue-50/70 dark:bg-blue-950/40 border border-blue-100 dark:border-blue-900/50 rounded-xl"
            >
                <p class="text-xs font-medium text-gray-600 dark:text-gray-400">
                    Total Users
                </p>
                <p
                    class="text-2xl font-extrabold text-blue-600 dark:text-blue-400 mt-0.5"
                >
                    {{ overview.totalUsers || 0 }}
                </p>
                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">
                    <span
                        class="font-semibold text-emerald-600 dark:text-emerald-400"
                        >+{{ overview.recentRegistrations || 0 }}</span
                    >
                    this month
                </p>
            </div>
            <div
                class="p-3.5 bg-emerald-50/70 dark:bg-emerald-950/40 border border-emerald-100 dark:border-emerald-900/50 rounded-xl"
            >
                <p class="text-xs font-medium text-gray-600 dark:text-gray-400">
                    Active Users
                </p>
                <p
                    class="text-2xl font-extrabold text-emerald-600 dark:text-emerald-400 mt-0.5"
                >
                    {{ overview.activeUsers || 0 }}
                </p>
                <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-1">
                    Last 7 days
                </p>
            </div>
        </div>

        <!-- Role Distribution Progress Bars -->
        <div>
            <h4
                class="text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 mb-3"
            >
                User Roles Distribution
            </h4>
            <div class="space-y-3">
                <div
                    v-for="role in roleDistributionList"
                    :key="role.label"
                    class="flex items-center justify-between group"
                >
                    <span
                        class="text-xs font-medium text-gray-700 dark:text-gray-300 w-28 truncate"
                    >
                        {{ role.label }}
                    </span>
                    <div class="flex items-center flex-1 justify-end ml-2">
                        <div
                            class="w-full max-w-[8rem] sm:max-w-[10rem] bg-gray-100 dark:bg-gray-700/80 rounded-full h-2 mr-3 overflow-hidden"
                        >
                            <div
                                class="h-2 rounded-full transition-all duration-500 ease-out"
                                :class="role.barColor"
                                :style="{
                                    width: getPercentage(
                                        role.count,
                                        overview.totalUsers,
                                    ),
                                }"
                            ></div>
                        </div>
                        <span
                            class="text-xs font-semibold text-gray-900 dark:text-gray-200 w-8 text-right"
                        >
                            {{ role.count }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";

const props = defineProps({
    overview: {
        type: Object,
        default: () => ({}),
    },
});

// Dynamic role distribution mapping to reduce boilerplate
const roleDistributionList = computed(() => [
    {
        label: "Administrator",
        count: props.overview.admins || 0,
        barColor: "bg-rose-500 dark:bg-rose-600",
    },
    {
        label: "Breeders",
        count: props.overview.breeders || 0,
        barColor: "bg-emerald-500 dark:bg-emerald-600",
    },
    {
        label: "Focal Persons",
        count: props.overview.focalPersons || 0,
        barColor: "bg-amber-500 dark:bg-amber-600",
    },
    {
        label: "Researchers",
        count: props.overview.researchers || 0,
        barColor: "bg-blue-500 dark:bg-blue-600",
    },
    {
        label: "TWG Managers",
        count: props.overview.twgManagers || 0,
        barColor: "bg-indigo-500 dark:bg-indigo-600",
    },
]);

const getPercentage = (value, total) => {
    if (!total) return "0%";
    return `${Math.round((value / total) * 100)}%`;
};
</script>
