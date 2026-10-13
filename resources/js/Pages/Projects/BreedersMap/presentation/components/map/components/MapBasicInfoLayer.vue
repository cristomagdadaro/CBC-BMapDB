<script setup>
import { computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import {
    X,
    User,
    Sprout,
    Building2,
    BookOpen,
    MapPin,
    Award,
    Lock,
    ExternalLink,
    TrendingUp,
    Fingerprint,
    LogIn,
    UserPlus,
} from "@lucide/vue";

const props = defineProps({
    item: {
        type: Object,
        default: null,
    },
    visible: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(["close"]);

const page = usePage();
const isAuthenticated = computed(() => !!page.props.auth?.user);

const DEFAULT_FALLBACK_IMAGE = "/img/logo_cbc.webp";

const isBreeder = computed(() => {
    if (!props.item) return false;
    return (
        props.item.data_type === "breeders" ||
        props.item.dataType === "breeders" ||
        !!props.item.affiliation
    );
});

const isCommodity = computed(() => {
    return !isBreeder.value;
});

const fullDetailHref = computed(() => {
    if (!props.item?.id) return "#";
    if (isBreeder.value) {
        return `/projects/breedersmap/breeder/${props.item.id}`;
    }
    return `/projects/breedersmap/commodity/${props.item.id}`;
});

const onImageError = (e) => {
    if (e?.target && e.target.src !== DEFAULT_FALLBACK_IMAGE) {
        e.target.src = DEFAULT_FALLBACK_IMAGE;
    }
};

const handleClose = () => {
    emit("close");
};
</script>

<template>
    <transition
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-6"
        enter-to-class="opacity-100 translate-y-0 sm:translate-x-0"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="opacity-100 translate-y-0 sm:translate-x-0"
        leave-to-class="opacity-0 translate-y-4 sm:translate-y-0 sm:translate-x-6"
    >
        <aside
            v-if="visible && item"
            @click.stop
            @mousedown.stop
            class="fixed sm:absolute bottom-0 sm:bottom-auto sm:top-4 right-0 sm:right-4 z-[1002] pointer-events-auto w-full sm:w-[380px] max-w-full sm:max-w-[420px] max-h-[85vh] sm:max-h-[calc(100vh-2rem)] flex flex-col bg-white/95 dark:bg-slate-900/95 backdrop-blur-md rounded-t-2xl sm:rounded-2xl shadow-2xl border border-gray-200/80 dark:border-slate-800/80 overflow-hidden text-gray-800 dark:text-gray-100"
        >
            <!-- Header Bar -->
            <div
                class="flex items-center justify-between px-4 py-3 border-b border-gray-100 dark:border-slate-800/80 bg-gray-50/70 dark:bg-slate-800/50 shrink-0"
            >
                <div class="flex items-center gap-2">
                    <span
                        v-if="isBreeder"
                        class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 dark:bg-emerald-900/50 dark:text-emerald-300"
                    >
                        <User class="w-3.5 h-3.5" />
                        Breeder Profile
                    </span>
                    <span
                        v-else
                        class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-800 dark:bg-amber-900/50 dark:text-amber-300"
                    >
                        <Sprout class="w-3.5 h-3.5" />
                        Commodity Profile
                    </span>
                    <span class="text-[0.65rem] text-gray-400 dark:text-gray-500 uppercase tracking-wider font-semibold">
                        Basic Information
                    </span>
                </div>
                <button
                    @click="handleClose"
                    class="p-1 rounded-lg text-gray-400 hover:text-gray-700 dark:hover:text-gray-200 hover:bg-gray-200/60 dark:hover:bg-slate-700/60 transition-colors"
                    title="Close details"
                >
                    <X class="w-4 h-4" />
                </button>
            </div>

            <!-- Scrollable Content Body -->
            <div class="flex-1 overflow-y-auto p-4 space-y-4">
                <!-- Image & Title Hero -->
                <div class="flex flex-col items-center text-center space-y-3">
                    <div
                        class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl bg-white dark:bg-slate-800 p-1.5 shadow-md ring-2 ring-emerald-500/20 dark:ring-emerald-400/20 flex items-center justify-center overflow-hidden"
                    >
                        <img
                            :src="item.image || DEFAULT_FALLBACK_IMAGE"
                            :alt="item.name || item.label || 'Image'"
                            @error="onImageError"
                            class="w-full h-full object-contain rounded-xl"
                            loading="lazy"
                        />
                    </div>
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-white leading-tight">
                            {{ item.name || item.label }}
                        </h3>
                        <p
                            v-if="!isBreeder && item.scientific_name && item.scientific_name !== 'N/A'"
                            class="text-xs sm:text-sm italic text-emerald-700 dark:text-emerald-400 font-medium"
                        >
                            {{ item.scientific_name }}
                        </p>
                        <p
                            v-else-if="isBreeder && item.affiliation && item.affiliation !== 'N/A'"
                            class="text-xs text-gray-500 dark:text-gray-400 font-medium"
                        >
                            {{ item.affiliation }}
                        </p>
                    </div>
                </div>

                <!-- Attributes Card (Breeder vs Commodity) -->
                <div class="bg-gray-50/80 dark:bg-slate-800/40 rounded-xl p-3 border border-gray-100 dark:border-slate-800 space-y-2.5 text-xs sm:text-sm">
                    <!-- Breeder Info Block -->
                    <template v-if="isBreeder">
                        <div class="flex items-start gap-2.5">
                            <Building2 class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5" />
                            <div class="min-w-0 flex-1">
                                <span class="text-gray-500 dark:text-gray-400 block text-[0.7rem] uppercase font-semibold">Affiliation</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ item.affiliation || 'N/A' }}</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-2.5">
                            <Award class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5" />
                            <div class="min-w-0 flex-1">
                                <span class="text-gray-500 dark:text-gray-400 block text-[0.7rem] uppercase font-semibold">Expertise</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ item.expertise || 'Not specified' }}</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-2.5">
                            <BookOpen class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5" />
                            <div class="min-w-0 flex-1">
                                <span class="text-gray-500 dark:text-gray-400 block text-[0.7rem] uppercase font-semibold">Research Interests</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ item.research_interest || 'Not specified' }}</span>
                            </div>
                        </div>

                        <div v-if="item.location && item.location !== 'N/A'" class="flex items-start gap-2.5">
                            <MapPin class="w-4 h-4 text-emerald-600 dark:text-emerald-400 shrink-0 mt-0.5" />
                            <div class="min-w-0 flex-1">
                                <span class="text-gray-500 dark:text-gray-400 block text-[0.7rem] uppercase font-semibold">Location</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ item.location }}</span>
                            </div>
                        </div>
                    </template>

                    <!-- Commodity Info Block -->
                    <template v-else>
                        <div class="flex items-start gap-2.5">
                            <User class="w-4 h-4 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" />
                            <div class="min-w-0 flex-1">
                                <span class="text-gray-500 dark:text-gray-400 block text-[0.7rem] uppercase font-semibold">Breeder</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ item.breeder || 'N/A' }}</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-2.5">
                            <Fingerprint class="w-4 h-4 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" />
                            <div class="min-w-0 flex-1">
                                <span class="text-gray-500 dark:text-gray-400 block text-[0.7rem] uppercase font-semibold">Variety / Accession No. / Germplasm</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ item.variety || item.accession || 'N/A' }}</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-2.5">
                            <TrendingUp class="w-4 h-4 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" />
                            <div class="min-w-0 flex-1">
                                <span class="text-gray-500 dark:text-gray-400 block text-[0.7rem] uppercase font-semibold">Yield</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">
                                    {{ item.yield !== null && item.yield !== undefined && item.yield !== 'N/A' ? `${item.yield} tons/ha` : 'Not specified' }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-start gap-2.5">
                            <MapPin class="w-4 h-4 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" />
                            <div class="min-w-0 flex-1">
                                <span class="text-gray-500 dark:text-gray-400 block text-[0.7rem] uppercase font-semibold">Location</span>
                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ item.location || 'N/A' }}</span>
                            </div>
                        </div>

                        <div class="flex items-start gap-2.5">
                            <Award class="w-4 h-4 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" />
                            <div class="min-w-0 flex-1">
                                <span class="text-gray-500 dark:text-gray-400 block text-[0.7rem] uppercase font-semibold">Unique Traits / Characteristics</span>
                                <p class="text-gray-700 dark:text-gray-300 text-xs leading-relaxed mt-0.5">
                                    {{ item.unique_traits || 'Not specified' }}
                                </p>
                            </div>
                        </div>
                    </template>
                </div>

                <!-- Access Guard & CTA Section -->
                <div v-if="isAuthenticated" class="pt-1">
                    <Link
                        :href="fullDetailHref"
                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl font-medium text-sm text-white bg-cbc-dark-green hover:bg-emerald-800 shadow-md hover:shadow-lg transition-all focus:outline-none focus:ring-2 focus:ring-emerald-500/50"
                    >
                        <ExternalLink class="w-4 h-4" />
                        View Full Details & Analytics
                    </Link>
                </div>

                <div
                    v-else
                    class="p-3.5 rounded-xl bg-amber-50/90 dark:bg-amber-950/40 border border-amber-200/80 dark:border-amber-800/60 space-y-2.5"
                >
                    <div class="flex items-start gap-2">
                        <Lock class="w-4 h-4 text-amber-600 dark:text-amber-400 shrink-0 mt-0.5" />
                        <div>
                            <h4 class="text-xs font-bold text-amber-900 dark:text-amber-300">
                                Detailed Information Restricted
                            </h4>
                            <p class="text-[0.75rem] text-amber-800/90 dark:text-amber-200/80 leading-relaxed mt-0.5">
                                Complete genomic profiles, contact info, regulatory certifications, and full records require an authenticated user account.
                            </p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 pt-1 pointer-events-auto">
                        <a
                            href="/login"
                            @click.stop
                            @mousedown.stop
                            class="pointer-events-auto cursor-pointer inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold text-white bg-cbc-dark-green hover:bg-emerald-800 shadow-sm transition-colors text-center"
                        >
                            <LogIn class="w-3.5 h-3.5" />
                            Log In
                        </a>
                        <a
                            href="/register"
                            @click.stop
                            @mousedown.stop
                            class="pointer-events-auto cursor-pointer inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-lg text-xs font-semibold text-emerald-800 dark:text-emerald-300 bg-white dark:bg-slate-800 border border-emerald-300 dark:border-emerald-700 hover:bg-emerald-50 dark:hover:bg-slate-700 transition-colors text-center"
                        >
                            <UserPlus class="w-3.5 h-3.5" />
                            Register
                        </a>
                    </div>
                </div>
            </div>
        </aside>
    </transition>
</template>
