<template>
    <nav v-if="crumbs" class="breadcrumb flex flex-row text-dark-color text-normal p-3 bg-transparent">
        <ul class="flex flex-row">
            <li v-if="crumbs.length === 1">
                <Link :href="crumbs[0].to" class="hover:text-gray-100 flex items-center gap-1 text-gray-800 px-3 py-2 rounded-lg bg-cbc-yellow shadow">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd" d="M12.293 4.293a1 1 0 0 1 1.414 1.414L9.414 10l4.293 4.293a1 1 0 0 1-1.414 1.414l-5-5a1 1 0 0 1 0-1.414l5-5Z" clip-rule="evenodd" />
                    </svg>
                    Back
                </Link>
            </li>
            <li v-else v-for="(crumb, index) in crumbs" :key="index">
                <Link :href="crumb.to" class="hover:text-cbc-dark-green">{{ crumb.label }}</Link>
                &nbsp;
                <span v-if="index < crumbs.length - 1" class="text-gray-300">/</span>
                &nbsp;
            </li>
        </ul>
    </nav>
</template>
<script>
import { Link } from '@inertiajs/vue3';
export default {
    components: {
        Link,
    },
    computed: {
        crumbs() {
            return this.$page.props.breadcrumbs;
        },
    },
};
</script>
