<script>
import { Link, router } from "@inertiajs/vue3";
import BreadCrumb from "@/Components/BreadCrumb.vue";
import {
    ChevronRight,
    ChevronDown,
    User as UserIcon,
    LogOut,
} from "@lucide/vue";
import User from "@/Modules/core/domain/auth/User";

export default {
    name: "SidebarLayout",
    components: {
        Link,
        BreadCrumb,
        ChevronRight,
        ChevronDown,
        UserIcon,
        LogOut,
    },
    props: {
        services: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            sidebarOpen: false,
            sidebarCollapsed: false,
            localServices: [],
            user: new User(this.$page.props.auth.user),
        };
    },
    created() {
        if (typeof window === "undefined") return;

        // Initialize localServices from props to handle reactivity (isOpen states)
        this.localServices = this.services.map((service) => {
            const isServiceHrefCurrent =
                service.href && service.href.startsWith("/")
                    ? typeof window !== "undefined" &&
                      window.location.pathname === service.href
                    : service.href
                      ? route().current(service.href)
                      : false;

            return {
                ...service,
                isOpen: Array.isArray(service.children)
                    ? isServiceHrefCurrent ||
                      !!service.children.find(
                          (child) =>
                              (child.href && child.href.startsWith("/")
                                  ? typeof window !== "undefined" &&
                                    window.location.pathname === child.href
                                  : route().current(child.href)) ||
                              (child.isVueRouter &&
                                  this.$route &&
                                  this.$route.name === child.href),
                      )
                    : false,
            };
        });

        const savedSidebarCollapsed = window.localStorage.getItem(
            "layout.sidebar.collapsed",
        );
        if (
            savedSidebarCollapsed === "true" ||
            savedSidebarCollapsed === "false"
        ) {
            this.sidebarCollapsed = savedSidebarCollapsed === "true";
        }

        router.on("navigate", () => {
            this.sidebarOpen = false;
            if (typeof document !== "undefined") {
                document.body.classList.remove("overflow-hidden");
            }
        });
    },
    watch: {
        services: {
            handler(newServices) {
                // Update local services when props change but keep isOpen states if possible
                this.localServices = newServices.map((service) => {
                    const existing = this.localServices.find(
                        (s) => s.label === service.label,
                    );
                    return {
                        ...service,
                        isOpen: existing ? existing.isOpen : false,
                    };
                });
            },
            deep: true,
        },
        "$route.name"() {
            // Update active state when route changes
            this.localServices = this.services.map((service) => {
                const existing = this.localServices.find(
                    (s) => s.label === service.label,
                );
                const isServiceHrefCurrent = this.isServiceActive(service);
                return {
                    ...service,
                    isOpen: Array.isArray(service.children)
                        ? isServiceHrefCurrent ||
                          !!service.children.find((child) =>
                              this.isChildActive(child),
                          ) ||
                          (existing ? existing.isOpen : false)
                        : false,
                };
            });
        },
    },
    methods: {
        getComponentType(child, service) {
            if (child.isVueRouter) {
                if (service && service.href) {
                    let baseRoute = service.href;
                    if (baseRoute.endsWith(".index")) {
                        baseRoute = baseRoute.replace(".index", "");
                    }
                    if (
                        route().current(baseRoute + ".*") ||
                        route().current(baseRoute)
                    ) {
                        return "router-link";
                    }
                }
                return "Link";
            }
            if (child.href && child.href.startsWith("/")) {
                return "a";
            }
            return "Link";
        },
        getBindingObject(child, service) {
            const prop =
                this.getComponentType(child, service) === "router-link"
                    ? "to"
                    : "href";
            return { [prop]: this.getBindingValue(child, service) };
        },
        getParentBindingObject(service) {
            const prop = service.isVueRouter ? "to" : "href";
            let val;
            if (service.isVueRouter) {
                val = { name: service.href };
            } else if (service.href && service.href.startsWith("/")) {
                val = service.href;
            } else {
                val = route(service.href);
            }
            return { [prop]: val };
        },
        getBindingValue(child, service) {
            if (this.getComponentType(child, service) === "router-link") {
                return { name: child.href };
            }
            if (child.href && child.href.startsWith("/")) {
                return child.href;
            }
            if (child.isVueRouter && this.$router) {
                try {
                    return this.$router.resolve({ name: child.href }).href;
                } catch (e) {
                    // Fallback if router cannot resolve
                }
            }
            return route(child.href);
        },
        toggleSidebarCollapse() {
            this.sidebarCollapsed = !this.sidebarCollapsed;
            if (typeof window !== "undefined") {
                window.localStorage.setItem(
                    "layout.sidebar.collapsed",
                    String(this.sidebarCollapsed),
                );
            }
        },
        closeSidebar() {
            this.sidebarOpen = false;
        },
        isServiceActive(service) {
            if (!service) return false;
            if (service.href) {
                if (service.isVueRouter && this.$route) {
                    if (this.$route.name === service.href) return true;
                } else if (service.href.startsWith("/")) {
                    if (typeof window !== "undefined") {
                        return window.location.pathname === service.href;
                    }
                } else if (route().current(service.href)) {
                    return true;
                }
            }
            if (Array.isArray(service.children)) {
                return service.children.some((child) =>
                    this.isChildActive(child),
                );
            }
            return false;
        },
        isChildActive(child) {
            if (child.isVueRouter && this.$route) {
                return this.$route.name === child.href;
            }
            if (child.href && child.href.startsWith("/")) {
                if (typeof window !== "undefined") {
                    return window.location.pathname === child.href;
                }
                return false;
            }
            return route().current(child.href);
        },
        logout() {
            router.post(route("logout"));
            localStorage.clear();
        },
    },
};
</script>

<template>
    <div
        class="flex z-30 min-h-screen bg-slate-50 text-slate-900 dark:bg-[#0B1120] dark:text-slate-100 transition-colors duration-200"
    >
        <!-- Sidebar Navigation (Desktop) -->
        <Transition
            enter-active-class="transition-all duration-300 ease-in-out"
            enter-from-class="opacity-0 -translate-x-full"
            enter-to-class="opacity-100 translate-x-0"
            leave-active-class="transition-all duration-300 ease-in-out"
            leave-from-class="opacity-100 translate-x-0"
            leave-to-class="opacity-0 -translate-x-full"
        >
            <aside
                class="sticky inset-y-0 left-0 top-0 z-40 hidden max-h-screen overflow-visible bg-white/70 shadow-lg backdrop-blur-xl transition-all duration-300 xl:sticky xl:flex xl:flex-col xl:shadow-none dark:border-slate-800/60 dark:bg-slate-900/70"
                :class="sidebarCollapsed ? 'w-20' : 'w-64'"
            >
                <!-- Sidebar Header -->
                <div
                    class="relative h-16 border-b border-slate-200/60 bg-transparent dark:border-slate-800/60"
                >
                    <div
                        v-if="!sidebarCollapsed"
                        class="flex h-full items-center pl-3 bg-cbc-dark-green dark:bg-gray-950"
                    >
                        <div class="flex items-center gap-3">
                            <img
                                v-if="
                                    $page.props.jetstream
                                        ?.managesProfilePhotos &&
                                    $page.props.auth?.user?.profile_photo_url
                                "
                                :src="$page.props.auth.user.profile_photo_url"
                                :alt="$page.props.auth?.user?.name"
                                class="h-10 w-10 rounded-full object-cover shadow-sm ring-2 ring-white dark:ring-gray-700"
                            />
                            <div
                                v-else
                                class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 text-lg font-bold text-white shadow-sm"
                            >
                                {{
                                    $page.props.auth?.user?.name
                                        ?.charAt(0)
                                        ?.toUpperCase() || "U"
                                }}
                            </div>
                            <div class="min-w-0 flex-1 leading-tight">
                                <div
                                    class="text-sm font-semibold uppercase text-slate-100 whitespace-nowrap"
                                >
                                    {{ user.getFullName || "User" }}
                                </div>
                                <div class="text-xs font-medium text-slate-200">
                                    {{
                                        $page.props.auth?.user?.email ||
                                        "Member"
                                    }}
                                </div>
                                <div class="text-xs font-medium text-slate-300">
                                    {{ user.getRole || "Member" }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        v-else
                        class="flex h-full items-center justify-center border-b border-slate-200/60 p-3 dark:border-slate-800/60"
                    >
                        <img
                            v-if="
                                $page.props.jetstream?.managesProfilePhotos &&
                                $page.props.auth?.user?.profile_photo_url
                            "
                            :src="$page.props.auth.user.profile_photo_url"
                            class="h-10 w-10 rounded-full object-cover ring-2 ring-white dark:ring-gray-700"
                        />
                        <div
                            v-else
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-emerald-500 to-teal-600 font-bold text-white"
                        >
                            {{
                                $page.props.auth?.user?.name
                                    ?.charAt(0)
                                    ?.toUpperCase() || "U"
                            }}
                        </div>
                    </div>
                </div>

                <!-- Collapsed Toggle (when collapsed) -->
                <button
                    @click="toggleSidebarCollapse"
                    :class="{ 'rotate-180': !sidebarCollapsed }"
                    class="absolute -right-3 top-20 z-50 transform rounded-full bg-emerald-600 p-1.5 text-white shadow-lg ring-2 ring-white transition-all duration-300 hover:scale-110 hover:bg-emerald-700 dark:bg-emerald-500 dark:ring-slate-900 dark:hover:bg-emerald-400"
                    title="Toggle sidebar"
                >
                    <ChevronRight class="w-4 h-4" />
                </button>

                <!-- Navigation Items -->
                <nav
                    class="custom-scrollbar flex-1 space-y-1 overflow-visible overflow-y-auto overflow-x-hidden px-3 py-4"
                >
                    <template
                        v-for="service in localServices"
                        :key="service.label"
                    >
                        <!-- Single Link (Inertia or Vue Router) -->
                        <component
                            :is="
                                service.isVueRouter
                                    ? 'router-link'
                                    : service.href &&
                                        service.href.startsWith('/')
                                      ? 'a'
                                      : 'Link'
                            "
                            v-if="!service.children || !service.children.length"
                            v-bind="getParentBindingObject(service)"
                            class="group relative flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200"
                            :class="
                                isServiceActive(service)
                                    ? 'border border-emerald-100/50 bg-emerald-50/80 text-emerald-700 shadow-sm dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-400'
                                    : 'border border-transparent text-slate-600 hover:bg-slate-100/50 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800/50 dark:hover:text-slate-200'
                            "
                        >
                            <component
                                :is="service.icon"
                                :class="[
                                    isServiceActive(service)
                                        ? 'text-emerald-600 dark:text-emerald-400'
                                        : 'text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300',
                                ]"
                                class="w-5 h-5 text-center flex-shrink-0 transition-colors"
                            />
                            <span v-if="!sidebarCollapsed" class="truncate">{{
                                service.label
                            }}</span>
                            <span
                                v-if="
                                    isServiceActive(service) &&
                                    !sidebarCollapsed
                                "
                                class="ml-auto h-1.5 w-1.5 rounded-full bg-emerald-500 dark:bg-emerald-400"
                            ></span>

                            <!-- Tooltip for collapsed state -->
                            <div
                                v-if="sidebarCollapsed"
                                class="invisible absolute left-full z-50 ml-2 whitespace-nowrap rounded-md bg-gray-900 px-2 py-1 text-xs text-white opacity-0 transition-all duration-200 group-hover:visible group-hover:opacity-100"
                            >
                                {{ service.label }}
                            </div>
                        </component>

                        <!-- Dropdown Group -->
                        <div v-else class="space-y-1">
                            <button
                                @click="service.isOpen = !service.isOpen"
                                class="group flex w-full items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition-all duration-200"
                                :class="
                                    isServiceActive(service)
                                        ? 'border border-emerald-100/50 bg-emerald-50/80 text-emerald-700 shadow-sm dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-400'
                                        : 'border border-transparent text-slate-600 hover:bg-slate-100/50 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800/50 dark:hover:text-slate-200'
                                "
                            >
                                <component
                                    :is="service.icon"
                                    :class="[
                                        isServiceActive(service)
                                            ? 'text-emerald-600 dark:text-emerald-400'
                                            : 'text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300',
                                    ]"
                                    class="w-5 h-5 text-center flex-shrink-0 transition-colors"
                                />
                                <span
                                    v-if="!sidebarCollapsed"
                                    class="flex-1 truncate text-left"
                                    >{{ service.label }}</span
                                >
                                <ChevronDown
                                    v-if="!sidebarCollapsed"
                                    class="w-4 h-4 transition-transform duration-200"
                                    :class="
                                        service.isOpen
                                            ? 'rotate-180 text-emerald-500 dark:text-emerald-400'
                                            : 'text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300'
                                    "
                                />

                                <!-- Collapsed dropdown indicator -->
                                <div
                                    v-if="
                                        sidebarCollapsed &&
                                        isServiceActive(service)
                                    "
                                    class="absolute left-0 h-8 w-1 rounded-r-full bg-emerald-500 dark:bg-emerald-400"
                                ></div>
                            </button>

                            <!-- Expanded children -->
                            <div
                                v-if="!sidebarCollapsed && service.isOpen"
                                class="ml-5 space-y-1 border-l-2 border-slate-200/60 py-1 pl-3 dark:border-slate-800/60"
                            >
                                <component
                                    :is="getComponentType(child, service)"
                                    v-for="child in service.children"
                                    :key="child.label"
                                    v-bind="getBindingObject(child, service)"
                                    class="flex items-center gap-3 rounded-xl px-3 py-2 text-sm transition-all duration-200"
                                    :class="
                                        isChildActive(child)
                                            ? 'border border-emerald-100/30 bg-emerald-50/50 font-medium text-emerald-700 shadow-sm dark:border-emerald-500/10 dark:bg-emerald-500/10 dark:text-emerald-300'
                                            : 'border border-transparent text-slate-600 hover:bg-slate-100/50 hover:text-slate-900 dark:text-slate-400 dark:hover:bg-slate-800/50 dark:hover:text-slate-200'
                                    "
                                >
                                    <component
                                        v-if="child.icon"
                                        :is="child.icon"
                                        :class="[
                                            isChildActive(child)
                                                ? 'text-emerald-600 dark:text-emerald-400'
                                                : 'text-slate-400 group-hover:text-slate-500',
                                        ]"
                                        class="w-4 h-4 text-center transition-colors"
                                    />
                                    <span
                                        v-else
                                        class="h-1.5 w-1.5 rounded-full transition-colors"
                                        :class="
                                            isChildActive(child)
                                                ? 'bg-emerald-500 dark:bg-emerald-400'
                                                : 'bg-slate-300 dark:bg-slate-600'
                                        "
                                    ></span>
                                    <span class="truncate">{{
                                        child.label
                                    }}</span>
                                </component>
                            </div>
                        </div>
                    </template>
                </nav>

                <!-- Sidebar Footer -->
                <div
                    class="space-y-1 border-t border-slate-200/60 p-3 dark:border-slate-800/60"
                >
                    <Link
                        :href="route('profile.show')"
                        class="group relative flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-slate-700 transition-colors hover:bg-slate-100/50 dark:text-slate-300 dark:hover:bg-slate-800/50"
                    >
                        <UserIcon
                            class="w-5 h-5 text-slate-400 transition-colors group-hover:text-slate-600 dark:group-hover:text-slate-300"
                        />
                        <span v-if="!sidebarCollapsed">Profile Settings</span>
                        <div
                            v-if="sidebarCollapsed"
                            class="invisible absolute left-full z-50 ml-2 whitespace-nowrap rounded-md bg-slate-900 px-2 py-1 text-xs text-white opacity-0 transition-all duration-200 group-hover:visible group-hover:opacity-100"
                        >
                            Profile
                        </div>
                    </Link>
                    <button
                        @click="logout"
                        class="group relative flex w-full items-center gap-3 rounded-xl border border-transparent px-3 py-2.5 text-sm font-medium text-red-600 transition-colors hover:bg-red-50/50 dark:text-red-400 dark:hover:bg-red-500/10"
                    >
                        <LogOut
                            class="w-5 h-5 transition-transform group-hover:scale-110"
                        />
                        <span v-if="!sidebarCollapsed">Log Out</span>
                        <div
                            v-if="sidebarCollapsed"
                            class="invisible absolute left-full z-50 ml-2 whitespace-nowrap rounded-md bg-slate-900 px-2 py-1 text-xs text-white opacity-0 transition-all duration-200 group-hover:visible group-hover:opacity-100"
                        >
                            Log Out
                        </div>
                    </button>
                </div>
            </aside>
        </Transition>

        <!-- Main Workspace Area -->
        <div class="flex min-w-0 flex-1 flex-col h-screen overflow-hidden">
            <!-- AppLayout's Top Navbar logic usually goes above here, but since this wraps the content,
                 we will render the main content area with its own scrolling -->
            <main
                class="flex-1 w-full overflow-y-auto overflow-x-hidden flex flex-col bg-slate-50 dark:bg-[#0B1120] text-slate-900 dark:text-slate-100 transition-colors duration-200"
            >
                <slot name="topbar" />

                <BreadCrumb
                    class="dark:bg-slate-900 dark:border-slate-800/60 shadow-sm"
                />

                <div class="flex-1">
                    <slot name="content" />
                </div>
            </main>
        </div>
    </div>
</template>

<style scoped>
/* Scrollbar adjustments */
.custom-scrollbar::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}
.custom-scrollbar::-webkit-scrollbar-track {
    background: transparent;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}
.dark .custom-scrollbar::-webkit-scrollbar-thumb {
    background: #475569;
}
</style>
