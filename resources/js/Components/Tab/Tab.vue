<script>
export default {
    name: 'Tab',
    props: {
        tabs: {
            type: Array,
            required: true,
            default: () => [],
        },
        isLoading: {
            type: Boolean,
            default: false,
        },
        // Optional custom key to scope persistence per Tab instance/page
        persistenceKey: {
            type: String,
            default: null,
        },
    },
    data() {
        return {
            activeIndex: null,
        };
    },
    computed: {
        storageKey() {
            // Scope to route name by default, so different pages keep their own last tab
            const routeKey = this.$route?.name ? `:${this.$route.name}` : '';
            return `Tab:active:${this.persistenceKey || routeKey}`;
        },
    },
    methods: {
        getIdentifier(tab) {
            // Persist a stable, small identifier: prefer route.name, fallback to tab.name
            return (tab && tab.route && tab.route.name) ? tab.route.name : (tab?.name || '');
        },
        setActiveTab(tab, idx) {
            // Do not mutate prop objects; just set the index and persist identifier
            const id = this.getIdentifier(tab);
            this.activeIndex = idx;
            try { localStorage.setItem(this.storageKey, id); } catch (_) {}
        },
        isActiveIdx(idx) {
            return this.activeIndex === idx;
        },
        resolveIndexByIdentifier(identifier) {
            if (!identifier) return -1;
            const norm = identifier;
            const current = this.$route?.name || '';
            // Try exact route.name match first
            let i = this.tabs.findIndex(t => t?.route?.name && t.route.name === norm);
            if (i !== -1) return i;
            // Try tab.name match
            i = this.tabs.findIndex(t => t?.name && t.name === norm);
            if (i !== -1) return i;
            // Special-case: allow comparing against current route variations
            const short = current.replace('projects.breedersmap.', '');
            i = this.tabs.findIndex(t => t?.route?.name && (t.route.name === current || t.route.name === short));
            return i;
        },
        updateActiveTab() {
            if (!Array.isArray(this.tabs) || this.tabs.length === 0) {
                this.activeIndex = null;
                return;
            }

            // 1) Prefer matching by current route
            const current = this.$route?.name || '';
            const short = current.replace('projects.breedersmap.', '');
            let idx = this.tabs.findIndex(t => t?.route?.name && (t.route.name === current || t.route.name === short));

            // 2) Else restore from localStorage identifier
            if (idx === -1) {
                let saved = null;
                try { saved = localStorage.getItem(this.storageKey); } catch (_) {}
                if (saved) idx = this.resolveIndexByIdentifier(saved);
            }

            // 3) Fallback to first tab
            if (idx === -1) idx = 0;

            this.activeIndex = idx;
        },
    },
    watch: {
        // When route changes, re-evaluate the active tab (route-linked tabs)
        $route() {
            this.updateActiveTab();
        },
        // If tabs are dynamic, ensure we still have a valid active index
        tabs: {
            deep: false,
            handler() {
                this.updateActiveTab();
            }
        }
    },
    created() {
        this.updateActiveTab();
    },
};
</script>

<template>
    <div v-if="tabs && tabs.length" class="flex flex-col bg-transparent rounded-lg overflow-hidden">
        <div class="z-10 flex gap-1 select-none p-4 bg-white max-w-screen overflow-x-auto">
            <template v-for="(tab, idx) in tabs" :key="tab.name || idx">
                <router-link
                    v-if="tab.route && tab.route.name"
                    @click="setActiveTab(tab, idx)"
                    class="py-2 px-3 rounded-md text-normal duration-300 active:scale-90"
                    :class="isActiveIdx(idx) ? 'bg-cbc-dark-green text-white scale-y-90 shadow-md' : 'bg-gray-300'"
                    :to="{ name: tab.route.name, params: { id: tab.route.params?.id || $route.params.id } }"
                >
                    {{ tab.label }}
                </router-link>
                <button
                    v-else
                    @click.prevent="setActiveTab(tab, idx)"
                    class="py-2 px-3 rounded-md text-normal duration-300 active:scale-90"
                    :class="isActiveIdx(idx) ? 'bg-cbc-dark-green text-white scale-y-90 shadow-md' : 'bg-gray-300'"
                >
                    {{ tab.label }}
                </button>
            </template>
        </div>
        <div class="z-10 bg-white min-h-fit px-4 pb-4" v-if="activeIndex !== null">
            <slot :name="tabs[activeIndex]?.name"/>
        </div>
    </div>
</template>
