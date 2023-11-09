<script>
import LoaderIcon from "@/Components/Icons/LoaderIcon.vue";

export default {
  components: {LoaderIcon},
    props: {
        tabs: {
            type: Array,
            required: true,
        },
        isLoading: {
          type: Boolean,
          default: false,
        },
    },
    data() {
        return {
            activeTab: null,
        };
    },
    methods: {
        isActiveTab(tab) {
            return this.activeTab === tab;
        },
        setActiveTab(tab) {
            this.activeTab = tab;
        },
    },
    mounted() {
        if (Array.isArray(this.tabs)) {
            this.activeTab = this.tabs.find((tab) => tab.active);
        } else {
            this.activeTab = this.tabs;
        }
    },
};
</script>
<template>
  <div class="flex flex-col">
    <div class="flex gap-1">
      <button
          v-for="tab in tabs"
          type="button"
          @click="setActiveTab(tab)"
          :class="tab.active ? 'bg-cbc-dark-green text-white' : 'bg-gray-300'"
          class="py-1 px-2 rounded-t-sm text-sm font-medium"
      >
        {{ tab.label }}
      </button>
    </div>
    <div v-if="activeTab" class="bg-white rounded-b-md">
      <!-- Show a loading icon if content is not loaded -->
      <div v-if="isLoading" class="flex items-center justify-center gap-1 py-4">
        <span class="animate-bounce">
          <loader-icon class="w-6 h-6 text-cbc-dark-green" />
        </span>
        loading...
      </div>
      <!-- Show the content when it's loaded -->
      <div v-else>
        <slot :name="activeTab.name"></slot>
      </div>
    </div>
  </div>
</template>
