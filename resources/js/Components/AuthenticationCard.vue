<template>
    <div class="min-h-screen flex flex-col sm:justify-center items-center bg-gray-100">
        <svg class="rotate-180 absolute top-0 shadow-md" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" viewBox="0 0 1439 560">
            <g mask="url(&quot;#SvgjsMask2042&quot;)" fill="none">
                <rect width="1439" height="560" x="0" y="0" fill="url(&quot;#SvgjsLinearGradient2043&quot;)"></rect>
                <path d="M 0,258 C 57.6,215 172.8,61.2 288,43 C 403.2,24.8 460.8,170.4 576,167 C 691.2,163.6 748.8,14.6 864,26 C 979.2,37.4 1037,213.2 1152,224 C 1267,234.8 1381.6,108.8 1439,80L1439 560L0 560z" fill="rgba(7, 152, 84, 1)"></path>
            </g>
        </svg>
        <svg class="rotate-180 absolute top-0 shadow-md" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" viewBox="0 0 1439 560">
            <g mask="url(&quot;#SvgjsMask2042&quot;)" fill="none">
                <rect width="1439" height="560" x="0" y="0" fill="url(&quot;#SvgjsLinearGradient2043&quot;)"></rect>
                <path d="M 0,390 C 96,408.8 288,479.6 480,484 C 672,488.4 768.2,411.2 960,412 C 1151.8,412.8 1343.2,472.8 1439,488L1439 560L0 560z" fill="rgba(0, 104, 55, 1)"></path>
            </g>
        </svg>

        <div class="w-full sm:max-w-2xl p-4 relative sm:rounded-lg bg-cbc-yellow sm:border-4 border-0 border-gray-100">
            <slot />
        </div>
    </div>
</template>
<script setup>
</script>
