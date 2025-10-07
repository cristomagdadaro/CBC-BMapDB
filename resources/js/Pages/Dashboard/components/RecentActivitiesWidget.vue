<template>
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-clock text-blue-500 mr-2"></i>
                Recent System Activities
            </h3>
            <button
                @click="refreshActivities"
                class="text-gray-500 hover:text-gray-700 transition"
            >
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>

        <div v-if="activities.length === 0" class="text-center py-8 text-gray-400">
            <i class="fas fa-inbox text-4xl mb-2"></i>
            <p>No recent activities</p>
        </div>

        <div v-else class="space-y-4 max-h-[500px] overflow-y-auto">
            <div
                v-for="(activity, index) in activities"
                :key="index"
                class="flex items-start p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition border-l-4"
                :class="getActivityColor(activity.type)"
            >
                <div class="ml-3 flex-1">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">
                                {{ activity.title }}
                            </p>
                            <p class="text-xs text-gray-600 mt-1">
                                {{ activity.institute }}
                            </p>
                            <div class="flex items-center mt-2 text-xs text-gray-500">
                                <span v-if="activity.role" class="bg-gray-200 px-2 py-0.5 rounded">{{ activity.role }}</span>
                                <span class="mx-2">•</span>
                                <span>{{ activity.action }}</span>
                            </div>
                        </div>
                        <span class="text-xs text-gray-400 ml-2 whitespace-nowrap">
                            {{ formatTime(activity.timestamp) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import moment from 'moment';

const props = defineProps({
    activities: {
        type: Array,
        default: () => []
    }
});

const emit = defineEmits(['refresh']);

const getActivityColor = (type) => {
    const colors = {
        user_registration: 'border-blue-500',
        user_activity: 'border-green-500',
        commodity_creation: 'border-yellow-500',
        default: 'border-gray-500'
    };
    return colors[type] || colors.default;
};

const getIconBg = (type) => {
    const colors = {
        user_registration: 'bg-blue-500',
        user_activity: 'bg-green-500',
        commodity_creation: 'bg-yellow-500',
        default: 'bg-gray-500'
    };
    return colors[type] || colors.default;
};

const getIcon = (type) => {
    const icons = {
        user_registration: 'fas fa-user-plus',
        user_activity: 'fas fa-user-check',
        commodity_creation: 'fas fa-box-open',
        default: 'fas fa-circle'
    };
    return icons[type] || icons.default;
};

const formatTime = (time) => {
    return moment(time).fromNow();
};

const refreshActivities = () => {
    emit('refresh');
};
</script>
