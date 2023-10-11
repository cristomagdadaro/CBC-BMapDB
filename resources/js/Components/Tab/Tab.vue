<script setup>
import {onMounted, ref} from "vue";

const { tabs } = defineProps({
    tabs: Array,
})
const activeTab = ref(null);

const setActiveTab = function (tab) {
    activeTab.value = tab;
};

onMounted(() => {
    if (Array.isArray(tabs)) {
        activeTab.value = tabs.find((tab) => tab.active);
    } else {
        activeTab.value = tabs;
    }
});

//function that check if the active tab is the current tab
const isActiveTab = function (tab) {
    return activeTab.value === tab;
};
</script>
<template>
    <div class="flex flex-col">
        <div class="flex gap-0.5">
            <button v-for="tab in tabs" type="button" @click="setActiveTab(tab)" :class="tab.active?'bg-gray-900':'bg-gray-300'" class="py-1 px-2 text-gray-700 rounded-t-sm text-sm font-medium hover:bg-gray-400 active:bg-gray-500 active:text-gray-200 duration-300">{{  tab.label }}</button>
        </div>
        <div v-if="activeTab" class="sm:px-4 py-2 p-1 bg-white rounded-b-md">
            <slot :name="activeTab.name"></slot>
        </div>
    </div>
</template>
