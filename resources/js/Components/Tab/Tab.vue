<script>
import LoaderIcon from "@/Components/Icons/LoaderIcon.vue";
import { Link } from '@inertiajs/vue3';
export default {
  components: {LoaderIcon, Link},
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
    mounted() {
        if (this.tabs) {
            this.activeTab = this.tabs[0];
            this.setActiveTab(this.activeTab);
        }
    }
};
</script>
<template>
    <div v-if="tabs" class="flex flex-col bg-transparent">
        <div class="z-10 flex gap-1 select-none p-4 bg-white max-w-screen overflow-x-auto">
            <router-link
                v-for="tab in tabs"
                :key="tab.name"
                @click.native="setActiveTab(tab)"
                class="py-2 px-3 rounded-md text-normal duration-300 active:scale-90"
                :class="isActiveTab(tab) ? 'bg-cbc-dark-green text-white scale-y-90 shadow-md' : 'bg-gray-300'"
                :to="tab.route"
            >
                {{ tab.label }}
            </router-link>
        </div>
        <div class="z-10 bg-white min-h-screen sm:px-4 px-2" v-if="activeTab">
            <slot :name="activeTab.name" />
        </div>
    </div>
</template>
