<script setup>
import { ref, onMounted, nextTick } from 'vue';
import { Chart, registerables } from 'chart.js';
import ApiService from "@/Modules/core/infrastructure/ApiService.ts";

// Register Chart.js components
Chart.register(...registerables);

// Reactive data
const totalProjects = ref(0);
const totalExperts = ref(0);
const totalServices = ref(0);
const totalProducts = ref(0);
const totalOnGoingProjects = ref({});
const topExperts = ref({});
const typeServices = ref({});
const isLoading = ref(true);
const lastUpdated = ref(null);

// Advanced metrics
const expertUtilization = ref(0);
const activeProjectsCount = ref(0);
const completedProjectsCount = ref(0);
const mostPopularService = ref('');
const completionRate = ref(0);

// Chart instances
let projectStatusChart = null;
let serviceTypeChart = null;
let topExpertsChart = null;

// Fetch summary data
const getSummary = async () => {
    try {
        isLoading.value = true;
        const axios = new ApiService(route('api.twg.summary'));
        const response = await axios.get();

        console.log('Dashboard data:', response.data);

        totalProjects.value = response.data.data.totalProjects || 0;
        totalExperts.value = response.data.data.totalExperts || 0;
        totalServices.value = response.data.data.totalServices || 0;
        totalProducts.value = response.data.data.totalProducts || 0;
        totalOnGoingProjects.value = response.data.data.totalOnGoingProjects || {};
        topExperts.value = response.data.data.topExperts || {};
        typeServices.value = response.data.data.typeServices || {};

        // Calculate advanced metrics
        expertUtilization.value = (totalProjects.value / totalExperts.value).toFixed(1);
        activeProjectsCount.value = totalOnGoingProjects.value['Active'] || 0;
        completedProjectsCount.value = totalOnGoingProjects.value['Completed'] || 0;
        mostPopularService.value = Object.keys(typeServices.value).reduce((a, b) => typeServices.value[a] > typeServices.value[b] ? a : b, '');
        completionRate.value = ((completedProjectsCount.value / totalProjects.value) * 100).toFixed(1);

        // Update last updated time
        lastUpdated.value = new Date().toISOString();

        isLoading.value = false;
    } catch (error) {
        console.error('Error fetching summary:', error);
        isLoading.value = false;
    }
};

// Initialize Project Status Chart (Doughnut)
const initializeProjectStatusChart = () => {
    const ctx = document.getElementById('chartProjectStatus');
    if (!ctx) return;

    if (projectStatusChart) {
        projectStatusChart.destroy();
    }

    const labels = Object.keys(totalOnGoingProjects.value);
    const data = Object.values(totalOnGoingProjects.value);

    projectStatusChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                label: 'Projects',
                data: data,
                backgroundColor: [
                    'rgba(34, 197, 94, 0.8)',  // Green for Active
                    'rgba(59, 130, 246, 0.8)',  // Blue for Completed
                    'rgba(251, 146, 60, 0.8)',  // Orange
                    'rgba(168, 85, 247, 0.8)',  // Purple
                ],
                borderColor: [
                    'rgba(34, 197, 94, 1)',
                    'rgba(59, 130, 246, 1)',
                    'rgba(251, 146, 60, 1)',
                    'rgba(168, 85, 247, 1)',
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        font: {
                            size: 12,
                            family: "'Inter', sans-serif"
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
};

// Initialize Service Type Chart (Bar)
const initializeServiceTypeChart = () => {
    const ctx = document.getElementById('chartServiceType');
    if (!ctx) return;

    if (serviceTypeChart) {
        serviceTypeChart.destroy();
    }

    const labels = Object.keys(typeServices.value);
    const data = Object.values(typeServices.value);

    serviceTypeChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Services',
                data: data,
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 1,
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y', // Horizontal bar chart
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `Count: ${context.parsed.x}`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                },
                y: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                }
            }
        }
    });
};

// Initialize Top Experts Chart (Horizontal Bar)
const initializeTopExpertsChart = () => {
    const ctx = document.getElementById('chartTopExperts');
    if (!ctx) return;

    if (topExpertsChart) {
        topExpertsChart.destroy();
    }

    const labels = Object.keys(topExperts.value);
    const data = Object.values(topExperts.value);

    topExpertsChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Projects',
                data: data,
                backgroundColor: 'rgba(168, 85, 247, 0.8)',
                borderColor: 'rgba(168, 85, 247, 1)',
                borderWidth: 1,
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return `Projects: ${context.parsed.y}`;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                }
            }
        }
    });
};

// Initialize all charts
const initializeCharts = async () => {
    await nextTick();
    initializeProjectStatusChart();
    initializeServiceTypeChart();
    initializeTopExpertsChart();
};

// Refresh dashboard data
const refreshDashboard = async () => {
    await getSummary();
    await initializeCharts();
};

// Export functions
const exportToExcel = () => {
    // Implement Excel export logic
    console.log('Exporting to Excel...');
};

const exportToPDF = () => {
    // Implement PDF export logic
    console.log('Exporting to PDF...');
};

// Lifecycle
onMounted(async () => {
    await getSummary();
    await initializeCharts();
});
</script>

<template>
    <div class="w-full px-4 py-6 space-y-6 bg-gray-50 min-h-screen">
        <!-- Enhanced Header with Actions -->
        <div class="bg-white rounded-xl shadow-sm p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800 mb-2">TWG Biotech Database</h1>
                    <p class="text-gray-600 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Last updated: {{ new Date(lastUpdated).toLocaleString() }}
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-wrap gap-3">
                    <button
                        @click="refreshDashboard"
                        :disabled="isLoading"
                        class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <svg class="w-5 h-5" :class="{ 'animate-spin': isLoading }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                        </svg>
                        Refresh
                    </button>

                    <button
                        @click="exportToExcel"
                        class="flex hidden items-center gap-2 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export Excel
                    </button>

                    <button
                        @click="exportToPDF"
                        class="flex hidden items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        Export PDF
                    </button>
                </div>
            </div>
        </div>

        <!-- Loading State -->
        <div v-if="isLoading" class="flex flex-col items-center justify-center py-20">
            <div class="animate-spin rounded-full h-16 w-16 border-b-4 border-blue-600 mb-4"></div>
            <p class="text-gray-600 font-medium">Loading dashboard data...</p>
        </div>

        <!-- Dashboard Content -->
        <div v-else class="space-y-6">
            <!-- KPI Cards Row 1: Main Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Experts Card -->
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium mb-1">Total Experts</p>
                            <h3 class="text-4xl font-bold">{{ totalExperts }}</h3>
                            <p class="text-purple-100 text-xs mt-2">
                                <span class="font-semibold">{{ expertUtilization }}</span> avg projects/expert
                            </p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Projects Card -->
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium mb-1">Total Projects</p>
                            <h3 class="text-4xl font-bold">{{ totalProjects }}</h3>
                            <p class="text-blue-100 text-xs mt-2">
                                <span class="font-semibold">{{ activeProjectsCount }}</span> active
                                <span class="mx-1">•</span>
                                <span class="font-semibold">{{ completedProjectsCount }}</span> completed
                            </p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Products Card -->
                <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium mb-1">Total Products</p>
                            <h3 class="text-4xl font-bold">{{ totalProducts }}</h3>
                            <p class="text-green-100 text-xs mt-2">
                                Biotech innovations
                            </p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Services Card -->
                <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl shadow-lg p-6 text-white transform hover:scale-105 transition-transform duration-200">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-orange-100 text-sm font-medium mb-1">Total Services</p>
                            <h3 class="text-4xl font-bold">{{ totalServices }}</h3>
                            <p class="text-orange-100 text-xs mt-2 truncate" :title="mostPopularService">
                                Top: {{ mostPopularService }}
                            </p>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-full p-3">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- KPI Cards Row 2: Performance Metrics -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Completion Rate -->
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-blue-500">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-gray-700 font-semibold">Completion Rate</h4>
                        <div class="bg-blue-100 rounded-full p-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex items-end gap-2">
                        <span class="text-3xl font-bold text-gray-800">{{ completionRate }}%</span>
                        <span class="text-sm text-gray-500 mb-1">of total projects</span>
                    </div>
                    <div class="mt-3 bg-gray-200 rounded-full h-2">
                        <div class="bg-blue-600 h-2 rounded-full transition-all duration-500" :style="{ width: completionRate + '%' }"></div>
                    </div>
                </div>

                <!-- Active Projects Indicator -->
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-green-500">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-gray-700 font-semibold">Active Projects</h4>
                        <div class="bg-green-100 rounded-full p-2">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex items-end gap-2">
                        <span class="text-3xl font-bold text-gray-800">{{ activeProjectsCount }}</span>
                        <span class="text-sm text-gray-500 mb-1">in progress</span>
                    </div>
                    <p class="text-xs text-gray-600 mt-2">{{ ((activeProjectsCount / totalProjects) * 100).toFixed(1) }}% of total</p>
                </div>

                <!-- Service Diversity -->
                <div class="bg-white rounded-xl shadow-lg p-6 border-l-4 border-purple-500">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="text-gray-700 font-semibold">Service Types</h4>
                        <div class="bg-purple-100 rounded-full p-2">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01" />
                            </svg>
                        </div>
                    </div>
                    <div class="flex items-end gap-2">
                        <span class="text-3xl font-bold text-gray-800">{{ Object.keys(typeServices).length }}</span>
                        <span class="text-sm text-gray-500 mb-1">unique types</span>
                    </div>
                    <p class="text-xs text-gray-600 mt-2">Diverse service offerings</p>
                </div>
            </div>

            <!-- Charts Row -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Project Status Chart -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <span class="w-1 h-6 bg-blue-600 rounded mr-3"></span>
                        Project Status Distribution
                    </h3>
                    <div class="h-64">
                        <canvas id="chartProjectStatus"></canvas>
                    </div>
                </div>

                <!-- Top Experts Chart -->
                <div class="bg-white rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <span class="w-1 h-6 bg-purple-600 rounded mr-3"></span>
                        Top 5 Experts by Projects
                    </h3>
                    <div class="h-64">
                        <canvas id="chartTopExperts"></canvas>
                    </div>
                </div>
            </div>

            <!-- Service Types Chart (Full Width) -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                    <span class="w-1 h-6 bg-green-600 rounded mr-3"></span>
                    Service Types Distribution
                </h3>
                <div class="h-96">
                    <canvas id="chartServiceType"></canvas>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Add smooth animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.bg-gradient-to-br {
    animation: fadeIn 0.5s ease-out;
}

/* Ensure canvas responsive */
canvas {
    max-width: 100%;
    height: auto !important;
}
</style>
