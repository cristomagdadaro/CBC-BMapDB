<script>
import LoaderIcon from "@/Components/Icons/LoaderIcon.vue";
import TransitionContainer from "@/Components/CustomDropdown/Components/TransitionContainer.vue";

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
  <div class="flex flex-col">
    <div class="flex gap-1 select-none">
      <button
          v-for="tab in tabs"
          @click="setActiveTab(tab)"
          class="py-1 px-2 rounded-t-sm text-sm font-medium duration-300 active:scale-90"
          :class="isActiveTab(tab) ? 'bg-cbc-dark-green text-white scale-y-90' : 'bg-gray-300'"
      >
        {{ tab.label }}
      </button>
    </div>
      <div class="bg-transparent rounded-b-md border shadow-lg">
          <Suspense>
              <template #default>
                  <!-- Show the content when it's loaded -->
                  <div v-if="activeTab">
                      <slot :name="activeTab.name"></slot>
                  </div>
                </template>
              <template #fallback>
                  <!-- Show a loading icon if content is not loaded -->
                  <div class="flex items-center justify-center gap-1 py-4">
                    <span class="animate-bounce">
                      <loader-icon class="w-6 h-6 text-cbc-dark-green" />
                    </span>
                          loading...
                  </div>
              </template>
          </Suspense>
      </div>
  </div>
</template>
