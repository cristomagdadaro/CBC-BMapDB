<script>
import LoaderIcon from "@/Components/Icons/LoaderIcon.vue";
import TransitionContainer from "@/Components/CustomDropdown/Components/TransitionContainer.vue";
import router from "@/router.js";

export default {
  components: {TransitionContainer, LoaderIcon},
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
        router() {
            return router
        },
        isActiveTab(tab) {
            return this.activeTab === tab;
        },
        setActiveTab(tab) {
            this.activeTab.active = false;
            this.activeTab = tab;
            this.activeTab.active = true;
        },
    },
    mounted() {
        if (Array.isArray(this.tabs)) {
            this.activeTab = this.tabs.find((tab) => tab.active);
        } else {
            this.activeTab = this.tabs[0];
        }
    },
};
</script>
<template>
  <div class="flex flex-col bg-transparent">
    <div class="flex gap-1 select-none p-2 mx-2 mt-2 shadow rounded-md bg-white">
      <button
          v-for="tab in tabs"
          @click="setActiveTab(tab)"
          class="py-2 px-3 rounded-md text-sm font-medium duration-300 active:scale-90"
          :class="isActiveTab(tab) ? 'bg-cbc-dark-green text-white scale-y-90 shadow-md' : 'bg-gray-300'"
      >
        {{ tab.label }}
      </button>
    </div>
      <div class="bg-transparent border p-2 m-2 shadow rounded-md bg-white" v-if="activeTab">
          <slot :name="activeTab.name" />
      </div>
  </div>
</template>
