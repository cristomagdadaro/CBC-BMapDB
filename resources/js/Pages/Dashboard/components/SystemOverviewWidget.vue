<template>
    <div class="bg-white rounded-lg shadow-lg p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-chart-line text-indigo-500 mr-2"></i>
            System Overview
        </h3>

        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="p-4 bg-blue-50 rounded-lg">
                <p class="text-sm text-gray-600">Total Users</p>
                <p class="text-2xl font-bold text-blue-600">{{ overview.totalUsers }}</p>
                <p class="text-xs text-gray-500 mt-1">
                    <span class="text-green-600">+{{ overview.recentRegistrations }}</span> this week
                </p>
            </div>
            <div class="p-4 bg-green-50 rounded-lg">
                <p class="text-sm text-gray-600">Active Users</p>
                <p class="text-2xl font-bold text-green-600">{{ overview.activeUsers }}</p>
                <p class="text-xs text-gray-500 mt-1">Last 7 days</p>
            </div>
        </div>

        <div class="mb-6">
            <h4 class="text-sm font-semibold text-gray-700 mb-3">User Roles Distribution</h4>
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Admins</span>
                    <div class="flex items-center">
                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-2">
                            <div
                                class="bg-red-500 h-2 rounded-full"
                                :style="{width: getPercentage(overview.totalAdmins, overview.totalUsers)}"
                            ></div>
                        </div>
                        <span class="text-sm font-medium">{{ overview.totalAdmins }}</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Breeders</span>
                    <div class="flex items-center">
                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-2">
                            <div
                                class="bg-green-500 h-2 rounded-full"
                                :style="{width: getPercentage(overview.totalBreeders, overview.totalUsers)}"
                            ></div>
                        </div>
                        <span class="text-sm font-medium">{{ overview.totalBreeders }}</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Focal Persons</span>
                    <div class="flex items-center">
                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-2">
                            <div
                                class="bg-yellow-500 h-2 rounded-full"
                                :style="{width: getPercentage(overview.totalFocalPersons, overview.totalUsers)}"
                            ></div>
                        </div>
                        <span class="text-sm font-medium">{{ overview.totalFocalPersons }}</span>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Researchers</span>
                    <div class="flex items-center">
                        <div class="w-32 bg-gray-200 rounded-full h-2 mr-2">
                            <div
                                class="bg-blue-500 h-2 rounded-full"
                                :style="{width: getPercentage(overview.totalResearchers, overview.totalUsers)}"
                            ></div>
                        </div>
                        <span class="text-sm font-medium">{{ overview.totalResearchers }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4">
            <div class="border-t pt-4">
                <h4 class="text-xs font-semibold text-gray-600 mb-2">Plant Breeders Map</h4>
                <div class="space-y-1">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-600">Breeders:</span>
                        <span class="font-medium">{{ overview.pbmap?.totalBreeders || 0 }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-600">Commodities:</span>
                        <span class="font-medium">{{ overview.pbmap?.totalCommodities || 0 }}</span>
                    </div>
                    <div class="flex justify-between text-xs text-green-600">
                        <span>Recent:</span>
                        <span class="font-medium">+{{ overview.pbmap?.recentCommodities || 0 }}</span>
                    </div>
                </div>
            </div>
            <div class="border-t pt-4">
                <h4 class="text-xs font-semibold text-gray-600 mb-2">TWG Biotech Database</h4>
                <div class="space-y-1">
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-600">Experts:</span>
                        <span class="font-medium">{{ overview.twgdb?.totalExperts || 0 }}</span>
                    </div>
                    <div class="flex justify-between text-xs">
                        <span class="text-gray-600">Projects:</span>
                        <span class="font-medium">{{ overview.twgdb?.totalProjects || 0 }}</span>
                    </div>
                    <div class="flex justify-between text-xs text-green-600">
                        <span>Recent:</span>
                        <span class="font-medium">+{{ overview.twgdb?.recentProjects || 0 }}</span>
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

