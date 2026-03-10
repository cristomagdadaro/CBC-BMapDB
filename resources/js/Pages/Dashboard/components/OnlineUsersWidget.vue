<template>
    <div class="bg-white rounded-lg shadow-lg p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-800 flex items-center">
                <i class="fas fa-users text-green-500 mr-2"></i>
                Online Users
            </h3>
            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded-full">
                {{ onlineUsers.length }} Online
            </span>
        </div>

        <div v-if="onlineUsers.length === 0" class="text-center py-8 text-gray-400">
            <i class="fas fa-user-slash text-4xl mb-2"></i>
            <p>No users currently online</p>
        </div>

        <div v-else class="space-y-3 max-h-96 overflow-y-auto">
            <div
                v-for="user in onlineUsers"
                :key="user.id"
                class="flex items-center p-3 bg-gray-50 rounded-lg hover:bg-gray-100 transition"
            >
                <div class="relative">
                    <img
                        :src="user.profile_photo_url"
                        :alt="user.name"
                        class="w-10 h-10 rounded-full"
                    />
                    <span class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-white rounded-full"></span>
                </div>
                <div class="ml-3 flex-1">
                    <p class="text-sm font-medium text-gray-900">{{ user.name }}</p>
                    <p class="text-xs text-gray-500">{{ user.role || 'User' }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-400">{{ formatTime(user.last_activity) }}</p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import moment from 'moment';

const props = defineProps({
    onlineUsers: {
        type: Array,
        default: () => []
    }
});

const formatTime = (time) => {
    return moment(time).fromNow();
};
</script>

