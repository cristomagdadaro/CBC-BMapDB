<script>
import ProfileEmptyIcon from "@/Components/Icons/ProfileEmptyIcon.vue";

export default {
    components: {
        ProfileEmptyIcon,
    },
    props: {
        data: {
            type: Object,
            required: true,
            default: () => ({
                agencyName: "Agency Name",
                contact: "Contact",
                experts: 0,
                projects: 0,
            }),
        }
    }
}
</script>

<template>
    <div class="relative w-full overflow-hidden rounded-2xl border border-gray-200/70 bg-white/80 p-5 shadow-sm backdrop-blur transition-all duration-300 hover:shadow-xl dark:border-gray-800/60 dark:bg-gray-900/60">
        <!-- subtle gradient highlight -->
        <div class="pointer-events-none absolute inset-x-0 -top-24 h-40 bg-gradient-to-b from-cyan-200/30 via-transparent to-transparent opacity-60 blur-2xl dark:from-cyan-400/10"></div>

        <!-- Header -->
        <div class="relative z-10 flex items-start justify-between gap-4 pb-4">
            <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-gradient-to-br from-cyan-500 to-blue-600 text-white shadow-sm ring-1 ring-white/40 dark:ring-gray-900/40">
                    <profile-empty-icon class="h-6 w-6 opacity-90" />
                </div>
                <div class="flex flex-col">
                    <h1 class="max-w-[22rem] truncate text-base font-semibold tracking-tight text-gray-900 dark:text-white">
                        {{ data.affiliation || data.agencyName || 'Affiliation' }}
                    </h1>
                    <h2 class="text-xs font-medium text-gray-500 dark:text-gray-400">Institute</h2>
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="relative z-10 grid grid-cols-2 gap-3 border-t border-b border-gray-200/70 py-4 dark:border-gray-800/60 sm:grid-cols-4">
            <div class="rounded-lg text-center bg-gray-50/70 p-3 transition-colors dark:bg-gray-800/60">
                <div class="text-[11px] uppercase tracking-wide text-gray-500 dark:text-gray-400">Experts</div>
                <div class="text-xl font-semibold text-gray-900 dark:text-white">{{ data.experts || 0 }}</div>
            </div>
            <div class="rounded-lg text-center bg-gray-50/70 p-3 transition-colors dark:bg-gray-800/60">
                <div class="text-[11px] uppercase tracking-wide text-gray-500 dark:text-gray-400">Projects</div>
                <div class="text-xl font-semibold text-gray-900 dark:text-white">{{ data.projects || 0 }}</div>
            </div>
            <div class="rounded-lg text-center bg-gray-50/70 p-3 transition-colors dark:bg-gray-800/60">
                <div class="text-[11px] uppercase tracking-wide text-gray-500 dark:text-gray-400">Products</div>
                <div class="text-xl font-semibold text-gray-900 dark:text-white">{{ data.products || 0 }}</div>
            </div>
            <div class="rounded-lg text-center bg-gray-50/70 p-3 transition-colors dark:bg-gray-800/60">
                <div class="text-[11px] uppercase tracking-wide text-gray-500 dark:text-gray-400">Services</div>
                <div class="text-xl font-semibold text-gray-900 dark:text-white">{{ data.services || 0 }}</div>
            </div>
        </div>

    </div>
</template>

<style scoped>

</style>
