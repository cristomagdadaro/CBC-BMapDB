<script setup>
import { ref, computed } from 'vue';
import Notification from "@/Components/Modal/Notification/Notification.ts";
import SuccessIcon from "@/Components/Icons/SuccessIcon.vue";
import WarningIcon from '@/Components/Icons/WarningIcon.vue';
import ErrorIcon from "@/Components/Icons/ErrorIcon.vue";
import FailedIcon from "@/Components/Icons/FailedIcon.vue";
import CloseIcon from "@/Components/Icons/CloseIcon.vue";
import BellIcon from "@/Components/Icons/BellIcon.vue";

const isOpen = ref(false);
const copiedId = ref(null);

const toggleDropdown = () => {
    isOpen.value = !isOpen.value;
};

const closeDropdown = () => {
    isOpen.value = false;
};

const notificationCount = computed(() => {
    return Notification.notifications.value.length;
});

const copyToClipboard = (notify, event) => {
    event.stopPropagation();
    const textToCopy = `Title: ${notify.title}\nMessage: ${notify.message}${notify.errno ? `\nError Number: ${notify.errno}` : ''}`;
    navigator.clipboard.writeText(textToCopy).then(() => {
        copiedId.value = notify.id;
        setTimeout(() => {
            copiedId.value = null;
        }, 1000);
    }).catch(err => {
        console.error('Could not copy text: ', err);
    });
};

const closeNotification = (notify, event) => {
    event.stopPropagation();
    notify.close(0);
};

const clearAll = () => {
    Notification.notifications.value = [];
};

// Close dropdown when clicking outside
const dropdownRef = ref(null);
const handleClickOutside = (event) => {
    if (dropdownRef.value && !dropdownRef.value.contains(event.target)) {
        closeDropdown();
    }
};

// Add event listener when mounted
if (typeof window !== 'undefined') {
    window.addEventListener('click', handleClickOutside);
}
</script>

<template>
    <div ref="dropdownRef" class="relative">
        <!-- Bell Button -->
        <button
            @click.stop="toggleDropdown"
            class="relative inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 hover:scale-105 active:scale-100 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150"
        >
            <slot name="icon">
                <bell-icon class="h-auto w-6" :class="notificationCount ? 'animate-wiggle' : ''" />
            </slot>

            <!-- Notification Badge -->
            <span
                v-if="notificationCount > 0"
                class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-500 rounded-full"
            >
                {{ notificationCount > 99 ? '99+' : notificationCount }}
            </span>
        </button>

        <!-- Dropdown Menu -->
        <transition
            enter-active-class="transition ease-out duration-200"
            enter-from-class="transform opacity-0 scale-95"
            enter-to-class="transform opacity-100 scale-100"
            leave-active-class="transition ease-in duration-150"
            leave-from-class="transform opacity-100 scale-100"
            leave-to-class="transform opacity-0 scale-95"
        >
            <div
                v-show="isOpen"
                class="absolute right-0 mt-2 w-96 max-w-[90vw] bg-white rounded-lg shadow-2xl ring-1 ring-black ring-opacity-5 z-50"
            >
                <!-- Header -->
                <div class="px-4 py-3 border-b border-gray-200 flex items-center justify-between bg-gray-50 rounded-t-lg">
                    <h3 class="text-sm font-semibold text-gray-900">
                        Notifications
                        <span v-if="notificationCount > 0" class="text-gray-500">({{ notificationCount }})</span>
                    </h3>
                    <button
                        v-if="notificationCount > 0"
                        @click="clearAll"
                        class="text-xs text-blue-600 hover:text-blue-800 font-medium"
                    >
                        Clear All
                    </button>
                </div>

                <!-- Notifications List -->
                <div class="max-h-96 overflow-y-auto">
                    <div v-if="notificationCount === 0" class="px-4 py-8 text-center text-gray-500">
                        <div class="mb-2">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </div>
                        <p class="text-sm">No notifications</p>
                    </div>

                    <div v-else class="divide-y divide-gray-100">
                        <div
                            v-for="notify in Notification.notifications.value"
                            :key="notify.id"
                            @click="copyToClipboard(notify, $event)"
                            class="px-4 py-3 hover:bg-gray-50 cursor-pointer transition-colors duration-150 relative"
                            :class="{
                                'bg-green-50': notify.type === 'success',
                                'bg-yellow-50': notify.type === 'warning',
                                'bg-red-50': notify.type === 'error' || notify.type === 'failed'
                            }"
                        >
                            <!-- Copied Overlay -->
                            <div
                                v-if="copiedId === notify.id"
                                class="absolute inset-0 bg-black bg-opacity-50 flex items-center justify-center z-10"
                            >
                                <span class="text-white font-medium">Copied!</span>
                            </div>

                            <div class="flex items-start gap-3">
                                <!-- Icon -->
                                <div class="flex-shrink-0 mt-0.5">
                                    <success-icon v-if="notify.type === 'success'" class="w-5 h-5 text-green-600" />
                                    <warning-icon v-else-if="notify.type === 'warning'" class="w-5 h-5 text-yellow-600" />
                                    <failed-icon v-else-if="notify.type === 'failed'" class="w-5 h-5 text-red-600" />
                                    <error-icon v-else class="w-5 h-5 text-red-600" />
                                </div>

                                <!-- Content -->
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ notify.title }}
                                        <span v-if="notify.errno" class="text-gray-500 text-xs">: {{ notify.errno }}</span>
                                    </p>
                                    <p class="text-sm text-gray-600 mt-1 break-words">
                                        {{ notify.message }}
                                    </p>
                                </div>

                                <!-- Close Button -->
                                <button
                                    @click="closeNotification(notify, $event)"
                                    class="flex-shrink-0 text-gray-400 hover:text-gray-600 transition-colors duration-150"
                                >
                                    <span class="sr-only">Close</span>
                                    <close-icon class="w-4 h-4 hover:rotate-90 transition-transform duration-300" />
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div v-if="notificationCount > 0" class="px-4 py-2 border-t border-gray-200 bg-gray-50 rounded-b-lg">
                    <p class="text-xs text-gray-500 text-center">
                        Click on a notification to copy details
                    </p>
                </div>
            </div>
        </transition>
    </div>
</template>

<style scoped>
@keyframes wiggle {
    0%, 100% { transform: rotate(0deg); }
    25% { transform: rotate(-10deg); }
    75% { transform: rotate(10deg); }
}

.animate-wiggle {
    animation: wiggle 0.5s ease-in-out infinite;
}
</style>
