<template>
    <div
        class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700/60 p-6 transition-colors duration-200"
    >
        <!-- Header -->
        <div class="flex items-center justify-between mb-4">
            <h3
                class="text-base font-semibold text-gray-900 dark:text-gray-100 flex items-center"
            >
                <i
                    class="fas fa-users text-emerald-500 dark:text-emerald-400 mr-2"
                ></i>
                Online Users
            </h3>
            <span
                class="inline-flex items-center gap-1.5 bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-400 border border-emerald-200/60 dark:border-emerald-800/60 text-xs font-semibold px-2.5 py-0.5 rounded-full"
            >
                <span
                    class="w-1.5 h-1.5 rounded-full bg-emerald-500 dark:bg-emerald-400 animate-pulse"
                ></span>
                {{ onlineUsers.length }} Online
            </span>
        </div>

        <!-- Empty State -->
        <div
            v-if="onlineUsers.length === 0"
            class="text-center py-8 text-gray-400 dark:text-gray-500"
        >
            <i class="fas fa-user-slash text-3xl mb-2 opacity-60"></i>
            <p class="text-xs font-medium">No users currently online</p>
        </div>

        <!-- Online User List -->
        <div
            v-else
            class="space-y-2 max-h-96 overflow-y-auto pr-1 scrollbar-thin scrollbar-thumb-gray-200 dark:scrollbar-thumb-gray-700"
        >
            <div
                v-for="user in onlineUsers"
                :key="user.id"
                class="flex items-center p-2.5 bg-gray-50 dark:bg-gray-700/40 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700/80 border border-transparent hover:border-gray-200 dark:hover:border-gray-600 transition-all duration-150"
            >
                <div class="relative flex-shrink-0">
                    <img
                        :src="user.profile_photo_url"
                        :alt="user.name"
                        class="w-9 h-9 rounded-full object-cover ring-2 ring-white dark:ring-gray-800"
                    />
                    <!-- Status Ring -->
                    <span
                        class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 border-2 border-white dark:border-gray-800 rounded-full"
                    ></span>
                </div>
                <div class="ml-3 flex-1 min-w-0">
                    <p
                        class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate"
                    >
                        {{ user.name }}
                    </p>
                    <p
                        class="text-xs text-gray-500 dark:text-gray-400 truncate"
                    >
                        {{ user.role || "User" }}
                    </p>
                </div>
                <div class="text-right flex-shrink-0 ml-2">
                    <p
                        class="text-[11px] text-gray-400 dark:text-gray-500 font-medium"
                    >
                        {{ formatTime(user.last_activity) }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import moment from "moment";

const props = defineProps({
    onlineUsers: {
        type: Array,
        default: () => [],
    },
});

const formatTime = (time) => {
    return moment(time).fromNow();
};
</script>
