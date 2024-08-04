<script>
import LoaderIcon from "@/Components/Icons/LoaderIcon.vue";
import TransitionContainer from "@/Components/CustomDropdown/Components/TransitionContainer.vue";
import { Link } from '@inertiajs/vue3';
export default {
  components: {TransitionContainer, LoaderIcon, Link},
    props: {
        tabs: {
            type: Array,
            required: true,
            default: null,
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
        setActiveTab(tab) {
            this.$router.push(tab.route);
        },
        isActiveTab(tab) {
            return this.$route.name === tab.route.name;
        }
    },
    watch: {
        $route() {
            if (this.tabs)
                this.activeTab = this.tabs.find(tab => tab.route.name === this.$route.name);
            else
                this.activeTab = null;
        }
    },
    created() {
        if (this.tabs)
            this.activeTab = this.tabs.find(tab => tab.route.name === this.$route.name);
        else
            this.activeTab = null;
    },
};
</script>
<template>
    <div v-if="tabs" class="flex flex-col bg-transparent">
        <div class="flex gap-1 select-none p-2 mx-2 mt-2 shadow rounded-md bg-white max-w-screen overflow-x-auto">
            <router-link
                v-for="tab in tabs"
                :key="tab.name"
                @click.native="setActiveTab(tab)"
                class="py-2 px-3 rounded-md text-sm font-medium duration-300 active:scale-90"
                :class="isActiveTab(tab) ? 'bg-cbc-dark-green text-white scale-y-90 shadow-md' : 'bg-gray-300'"
                :to="tab.route"
            >
                {{ tab.label }}
            </router-link>
        </div>
        <div class="bg-transparent border p-2 m-2 shadow rounded-md bg-white" v-if="activeTab">
            <slot :name="activeTab.name" />
        </div>
    </div>
</template>
