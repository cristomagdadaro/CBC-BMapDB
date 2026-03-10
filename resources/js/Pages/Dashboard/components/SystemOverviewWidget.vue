<template>
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-chart-line text-indigo-500 mr-2"></i>
            System Overview
        </h3>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="p-4 bg-blue-50 rounded-lg">
                <p class="text-sm text-gray-600">Total Users</p>
                <p class="text-2xl font-bold text-blue-600">{{ overview.totalUsers || 0 }}</p>
                <p class="text-xs text-gray-500 mt-1">
                    <span class="text-green-600">+{{ overview.recentRegistrations || 0 }}</span> this month
                </p>
            </div>
            <div class="p-4 bg-green-50 rounded-lg">
                <p class="text-sm text-gray-600">Active Users</p>
                <p class="text-2xl font-bold text-green-600">{{ overview.activeUsers || 0 }}</p>
                <p class="text-xs text-gray-500 mt-1">Last 7 days</p>
            </div>
        </div>

        <div class="mb-6">
            <h4 class="text-sm font-semibold text-gray-700 mb-3">User Roles Distribution</h4>
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Administrator</span>
                    <div class="flex items-center">
                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-2">
                            <div
                                class="bg-red-500 h-2 rounded-full"
                                :style="{width: getPercentage(overview.admins, overview.totalUsers)}"
                            ></div>
                        </div>
                        <span class="text-sm font-medium">{{ overview.admins || 0 }}</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Breeders</span>
                    <div class="flex items-center">
                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-2">
                            <div
                                class="bg-green-500 h-2 rounded-full"
                                :style="{width: getPercentage(overview.breeders, overview.totalUsers)}"
                            ></div>
                        </div>
                        <span class="text-sm font-medium">{{ overview.breeders || 0 }}</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Focal Persons</span>
                    <div class="flex items-center">
                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-2">
                            <div
                                class="bg-yellow-500 h-2 rounded-full"
                                :style="{width: getPercentage(overview.focalPersons, overview.totalUsers)}"
                            ></div>
                        </div>
                        <span class="text-sm font-medium">{{ overview.focalPersons || 0 }}</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Researchers</span>
                    <div class="flex items-center">
                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-2">
                            <div
                                class="bg-blue-500 h-2 rounded-full"
                                :style="{width: getPercentage(overview.researchers, overview.totalUsers)}"
                            ></div>
                        </div>
                        <span class="text-sm font-medium">{{ overview.researchers || 0 }}</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">TWG Managers</span>
                    <div class="flex items-center">
                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-2">
                            <div
                                class="bg-blue-500 h-2 rounded-full"
                                :style="{width: getPercentage(overview.twgManagers, overview.totalUsers)}"
                            ></div>
                        </div>
                        <span class="text-sm font-medium">{{ overview.twgManagers || 0 }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
defineProps({
    overview: {
        type: Object,
        default: () => ({})
    }
});

const getPercentage = (value, total) => {
    if (!total) return '0%';
    return `${Math.round((value / total) * 100)}%`;
};
</script>
