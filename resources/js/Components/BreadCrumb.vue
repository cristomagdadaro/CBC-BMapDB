<template>
    <nav v-if="crumbs" class="breadcrumb flex flex-row text-dark-color text-normal px-5 pt-3">
        <ul class="flex flex-row">
            <li v-if="crumbs.length === 1">
                <Link :href="crumbs[0].to" class="hover:text-gray-100 text-gray-800 px-3 py-1 rounded bg-cbc-yellow shadow">Back</Link>
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
