<script>
import LoaderIcon from "@/Components/Icons/LoaderIcon.vue";
import {Link} from '@inertiajs/vue3';

export default {
    components: { LoaderIcon, Link},
    props: {
        tabs: {
            type: Object,
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
            activeTab: JSON.parse(localStorage.getItem('activeTab')) || null,
        };
    },
    methods: {
        setActiveTab(tab) {
            if (tab.route)
                this.$router.push({name: tab.route.name, params: {id: tab.route.params?.id}});

            this.activeTab = tab;
            this.activeTab.active = true;
            localStorage.setItem('activeTab', JSON.stringify(tab));
        },
        isActiveTab(tab) {
            return this.activeTab === tab;
        },
        updateActiveTab() {
            if (!this.tabs || this.tabs.length === 0) {
                this.activeTab = null;
                return;
            }

            const savedTab = localStorage.getItem('activeTab') ? JSON.parse(localStorage.getItem('activeTab')) : null;

            this.activeTab = this.tabs.find(tab => {
                if (tab.route) {
                    const routeName = this.$route?.name?.replace('projects.breedersmap.', '');
                    return this.$route.name === tab.route.name || routeName === tab.route.name;
                }
                return tab.name === (savedTab?.name || this.tabs[0].name);
            }) || this.tabs[0];

            if (this.activeTab) {
                this.activeTab.active = true;
            }
        }

    },
    watch: {
        $route() {
            this.updateActiveTab();
        },
    },
    created() {
        this.updateActiveTab();
    },
};
</script>

<template>
    <div v-if="tabs" class="flex flex-col bg-transparent">
        <div class="z-10 flex gap-1 select-none p-4 bg-white max-w-screen overflow-x-auto">
           <template v-for="tab in tabs"
                     :key="tab.name" >
               <router-link
                   v-if="!!tab.route"
                   @click.native="setActiveTab(tab)"
                   class="py-2 px-3 rounded-md text-normal duration-300 active:scale-90"
                   :class="isActiveTab(tab) ? 'bg-cbc-dark-green text-white scale-y-90 shadow-md' : 'bg-gray-300'"
                   :to="{ name: tab.route.name, params: { id: tab.route.params?.id || $route.params.id } }"
               >
                   {{ tab.label }}
               </router-link>
               <button
                   v-else
                   @click.prevent="setActiveTab(tab)"
                   class="py-2 px-3 rounded-md text-normal duration-300 active:scale-90"
                   :class="isActiveTab(tab) ? 'bg-cbc-dark-green text-white scale-y-90 shadow-md' : 'bg-gray-300'"
                >
                     {{ tab.label }}
                </button>
           </template>
        </div>
        <div class="z-10 bg-white min-h-fit sm:px-4 pb-4" v-if="activeTab">
            <slot :name="activeTab.name"/>
        </div>
    </div>
</template>
