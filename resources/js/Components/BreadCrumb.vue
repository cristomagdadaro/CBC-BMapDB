<template>
    <nav class="breadcrumb flex flex-row text-gray-500">
        <ul class="flex flex-row">
            <li v-for="(crumb, index) in crumbs" :key="index">
                <Link :href="crumb.to" class="hover:text-cbc-olive-green">{{ crumb.label }}</Link>
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
           /* return [
                { to: '/', label: 'Home' },
                { to: '/projects', label: 'Projects' },
                { to: '/projects/twg', label: 'TWG' },
                { to: '/projects/twg/presentation', label: 'Presentation' },
                { to: '/projects/twg/presentation/public', label: 'Public' },
            ];*/
        },
    },
};
</script>
