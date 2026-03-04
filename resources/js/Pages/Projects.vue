<script>
import PageLayout from "@/Layouts/PageLayout.vue";
import { CBCProjectsPublic } from "@/Pages/constants.ts";
import { Link, Head, usePage } from '@inertiajs/vue3';
import { defineAsyncComponent, computed, ref, onMounted } from 'vue';
// Lazy load heavy components
const BmCollaborators = defineAsyncComponent(() => import("@/Pages/Projects/BreedersMap/presentation/components/misc/BmCollaborators.vue"));
const BmPriorityCom = defineAsyncComponent(() => import("@/Pages/Projects/BreedersMap/presentation/components/misc/BmPriorityCom.vue"));
const BmOverviewMap = defineAsyncComponent(() => import("@/Pages/Projects/BreedersMap/presentation/components/misc/BmOverviewMap.vue"));
const ParticlesBackground = defineAsyncComponent(() => import("@/Components/ParticlesBackground.vue"));
const AiChat = defineAsyncComponent(() => import("@/Pages/OpenAi/AiChat/AiChat.vue"));

export default {
    components: {
        AiChat,
        ParticlesBackground,
        BmOverviewMap,
        BmPriorityCom,
        BmCollaborators,
        PageLayout,
        Link,
        Head,
    },
    setup() {
        const page = usePage();
        const pbmapProject = computed(() => CBCProjectsPublic[0] || Object.values(CBCProjectsPublic)[0]);
        const biotwgProject = computed(() => CBCProjectsPublic[1] || Object.values(CBCProjectsPublic)[1]);
        const isHeroVisible = ref(false);

        onMounted(() => {
            setTimeout(() => { isHeroVisible.value = true; }, 100);
        });

        const faqItems = ref([
            { question: 'What is PIN and who can use it?', answer: 'PIN (Plant Breeders and Innovators Network) is a comprehensive platform designed for researchers, extension workers, students, and public stakeholders involved in crop biotechnology. It provides access to valuable data resources, research collaborations, and geographic information about plant breeding activities across the Philippines.', open: false },
            { question: 'How do I access the databases?', answer: 'You can access our databases by navigating to the "Browse Data" section. The Plant Breeders Map provides geographic visualization of breeding activities, while the Biotech TWG Database offers detailed project information. Some features may require registration for full access.', open: false },
            { question: 'Is the data on PIN regularly updated?', answer: 'Yes, our databases are regularly updated with contributions from partner institutions, research centers, and government agencies. We ensure data accuracy through a verification process involving our network of experts and contributors.', open: false },
            { question: 'How can I contribute data to PIN?', answer: 'To contribute data, you need to register for an account and request contributor access. Our team will review your application and provide guidelines for data submission. We welcome contributions from recognized research institutions and breeding programs.', open: false },
            { question: 'What support is available for new users?', answer: 'We offer comprehensive user guides, video tutorials, and a dedicated support team to help you get started. You can also reach out through our contact form or attend our regular training webinars.', open: false },
        ]);

        const features = [
            { title: 'Centralized Data', description: 'Access comprehensive agricultural data from multiple sources in one platform.', icon: 'database' },
            { title: 'Collaboration', description: 'Connect with researchers, breeders, and innovators across the Philippines.', icon: 'users' },
            { title: 'Geographic Insights', description: 'Visualize breeding activities and research distribution across regions.', icon: 'map' },
            { title: 'Data Analytics', description: 'Analyze trends and patterns to inform research decisions.', icon: 'chart' },
            { title: 'Crop Diversity', description: 'Explore information on various crops and breeding programs.', icon: 'sprout' },
            { title: 'Documentation', description: 'Access research papers, reports, and technical documents.', icon: 'file' },
        ];

        const databaseCards = [
            { stats: [{ label: 'Institutes', value: '70+' }, { label: 'Commodities', value: '25+' }, { label: 'Provinces', value: '45+' }], tags: ['Geographic Data', 'Institutes', 'Commodities'] },
            { stats: [{ label: 'Projects', value: '120+' }, { label: 'TWGs', value: '15' }, { label: 'Researchers', value: '300+' }], tags: ['Projects', 'Research', 'Collaboration'] },
        ];

        const heroStats = [
            { value: '70+', label: 'Partner Institutes' },
            { value: '25+', label: 'Commodities' },
            { value: '120+', label: 'Research Projects' },
            { value: '300+', label: 'Researchers' },
        ];

        const toggleFaq = (index) => { faqItems.value[index].open = !faqItems.value[index].open; };

        return {
            CBCProjectsPublic,
            page,
            pbmapProject,
            biotwgProject,
            isHeroVisible,
            faqItems,
            features,
            databaseCards,
            heroStats,
            toggleFaq,
        };
    },
};
</script>

<template>
    <Head title="Welcome"/>
    <page-layout>
        <!-- Hero Section -->
        <section class="relative min-h-screen flex items-center justify-center overflow-hidden">
            <div id="header-main-first" class="absolute inset-0 z-0">
                <particles-background id="header-particles-js"/>
                <div class="absolute inset-0 bg-gradient-to-b from-cbc-dark-green/80 via-cbc-dark-green/50 to-cbc-dark-green/90"></div>
            </div>
            <div class="absolute inset-0 z-10 pointer-events-none overflow-hidden">
                <div v-for="i in 6" :key="i" class="absolute w-2 h-2 bg-white/20 rounded-full animate-float"
                     :style="{ left: (15 + i * 15) + '%', top: (20 + (i % 3) * 25) + '%', animationDelay: (i * 0.5) + 's' }"></div>
            </div>
            <div class="relative z-20 section-padding pt-32 pb-20">
                <div class="container-custom text-center">
                    <div :class="['inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full text-white/90 text-sm font-medium mb-8 transition-all duration-700', isHeroVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4']">
                        <svg class="w-4 h-4 text-pin-lime" fill="currentColor" viewBox="0 0 24 24"><path d="M17 8C8 10 5.9 16.17 3.82 21.34l1.89.66L7 19h2l2 3 1-3h2l2-3 2 3 1-3c2-3 2-8-2-10z"/></svg>
                        <span>Empowering Agricultural Innovation</span>
                    </div>
                    <h1 :class="['text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold text-white font-display leading-tight mb-6 transition-all duration-700 delay-100', isHeroVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8']">
                        <span class="block">Plant Breeders and</span>
                        <span class="block text-pin-lime">Innovators Network</span>
                    </h1>
                    <p :class="['text-lg sm:text-xl text-white/80 max-w-2xl mx-auto mb-10 transition-all duration-700 delay-200', isHeroVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8']">
                        Empowering crop biotechnology research with innovation, one discovery at a time.
                        Access comprehensive data, connect with researchers, and drive agricultural advancement.
                    </p>
                    <div :class="['flex flex-col sm:flex-row items-center justify-center gap-4 transition-all duration-700 delay-300', isHeroVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8']">
                        <Link v-if="pbmapProject" :href="route(pbmapProject.route_public)"
                              class="group flex items-center gap-2 px-8 py-4 bg-pin-green hover:bg-pin-green-dark text-white rounded-xl font-semibold transition-all hover:shadow-xl hover:-translate-y-1 focus-ring">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 1 3 3 3h10c2 0 3-1 3-3V7c0-2-1-3-3-3H7C5 4 4 5 4 7z"/></svg>
                            Explore Databases
                            <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                        </Link>
                        <Link :href="route('support.what-is-pin')"
                              class="group flex items-center gap-2 px-8 py-4 bg-white/10 hover:bg-white/20 backdrop-blur-sm text-white rounded-xl font-semibold transition-all focus-ring">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Learn More
                        </Link>
                    </div>
                    <div :class="['flex flex-col sm:flex-row justify-center items-center gap-6 mt-12 transition-all duration-700 delay-400', isHeroVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8']">
                        <Link v-for="project in CBCProjectsPublic" :key="project.id" :href="route(project.route_public)"
                              class="flex items-center gap-3 px-6 py-3 bg-white/10 backdrop-blur-sm rounded-xl text-white hover:bg-white/20 transition-all hover:-translate-y-1 active:scale-95 duration-200">
                            <img :src="project.logo" :alt="project.label" class="w-auto h-[3rem]" loading="lazy"/>
                            <span class="text-sm font-semibold uppercase">{{ project.label }}</span>
                        </Link>
                    </div>
                    <div :class="['grid grid-cols-2 md:grid-cols-4 gap-6 mt-16 max-w-3xl mx-auto transition-all duration-700 delay-500', isHeroVisible ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-8']">
                        <div v-for="(stat, index) in heroStats" :key="index" class="text-center">
                            <p class="text-3xl sm:text-4xl font-bold text-pin-lime font-display">{{ stat.value }}</p>
                            <p class="text-sm text-white/70 mt-1">{{ stat.label }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-20 animate-bounce">
                <svg class="w-6 h-6 text-white/50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </div>
        </section>

        <!-- Database Cards Section -->
        <section class="py-20 lg:py-32 bg-white">
            <div class="section-padding">
                <div class="container-custom">
                    <div class="text-center max-w-2xl mx-auto mb-16">
                        <span class="badge-primary mb-4 inline-flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 1 3 3 3h10c2 0 3-1 3-3V7c0-2-1-3-3-3H7C5 4 4 5 4 7z"/></svg>
                            Data Resources
                        </span>
                        <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-4 font-display">Explore Our Databases</h2>
                        <p class="text-lg text-gray-600">Access comprehensive agricultural data through our specialized databases designed for researchers, breeders, and innovators.</p>
                    </div>
                    <div class="grid lg:grid-cols-2 gap-8">
                        <Link v-for="(project, index) in Object.values(CBCProjectsPublic)" :key="project.id" :href="route(project.route_public)"
                              class="group relative block rounded-2xl overflow-hidden shadow-card hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 focus-ring">
                            <div class="relative h-80 lg:h-96 overflow-hidden">
                                <div class="w-full h-full bg-gradient-to-br from-pin-green to-cbc-dark-green flex items-center justify-center">
                                    <img :src="project.logo" :alt="project.label" class="w-auto h-[6rem] opacity-30 group-hover:scale-110 transition-transform duration-700" loading="lazy"/>
                                </div>
                                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent"></div>
                            </div>
                            <div class="absolute inset-0 flex flex-col justify-end p-6 lg:p-8">
                                <div v-if="databaseCards[index]" class="flex flex-wrap gap-2 mb-4">
                                    <span v-for="tag in databaseCards[index].tags" :key="tag" class="px-3 py-1 bg-white/20 backdrop-blur-sm text-white text-xs font-medium rounded-full">{{ tag }}</span>
                                </div>
                                <h3 class="text-2xl lg:text-3xl font-bold text-white mb-3 group-hover:text-pin-lime transition-colors font-display">{{ project.label }}</h3>
                                <p class="text-white/80 text-sm lg:text-base mb-6 line-clamp-3">{{ project.description }}</p>
                                <div v-if="databaseCards[index]" class="flex items-center gap-6 mb-6">
                                    <div v-for="stat in databaseCards[index].stats" :key="stat.label">
                                        <p class="text-2xl font-bold text-pin-lime font-display">{{ stat.value }}</p>
                                        <p class="text-xs text-white/70">{{ stat.label }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 text-white font-medium group-hover:text-pin-lime transition-colors">
                                    <span>Explore Database</span>
                                    <svg class="w-5 h-5 group-hover:translate-x-2 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                                </div>
                            </div>
                        </Link>
                    </div>
                    <div class="mt-12 text-center">
                        <p class="text-gray-500 flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            More databases coming soon. Stay tuned for updates!
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Overview Map -->
        <section class="py-20 lg:py-32 bg-pin-gray">
            <div class="section-padding"><div class="container-custom"><bm-overview-map/></div></div>
        </section>

        <!-- Priority Commodities -->
        <section class="py-20 lg:py-32 bg-white">
            <div class="section-padding"><div class="container-custom"><bm-priority-com/></div></div>
        </section>

        <!-- About Section -->
        <section class="py-20 lg:py-32 bg-white">
            <div class="section-padding">
                <div class="container-custom">
                    <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
                        <div>
                            <span class="badge-primary mb-4 inline-flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                About PIN
                            </span>
                            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-6 font-display">Empowering Crop Biotechnology Research</h2>
                            <div class="space-y-4 text-gray-600">
                                <p>The Plant Breeders and Innovators Network (PIN) is a specialized online platform developed by the DA - Crop Biotechnology Center. It serves as a centralized repository of essential information meticulously curated to support crop biotechnology research endeavors across the Philippines.</p>
                                <p>Within this digital resource, you will find a comprehensive collection of data, tools, and resources designed to facilitate scientific investigations, accelerate discoveries, and drive innovation in the field of crop biotechnology.</p>
                            </div>
                            <div class="mt-8 flex flex-wrap gap-4">
                                <Link v-if="pbmapProject" :href="route(pbmapProject.route_public)" class="btn-primary inline-flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 1 3 3 3h10c2 0 3-1 3-3V7c0-2-1-3-3-3H7C5 4 4 5 4 7z"/></svg>
                                    Explore Data
                                </Link>
                                <Link :href="route('support.what-is-pin')" class="btn-secondary inline-flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    Learn More
                                </Link>
                            </div>
                        </div>
                        <div class="grid sm:grid-cols-2 gap-4">
                            <div v-for="feature in features" :key="feature.title" class="p-6 bg-pin-gray rounded-xl hover:bg-pin-green-light transition-colors group">
                                <div class="w-12 h-12 bg-pin-green/10 rounded-xl flex items-center justify-center text-pin-green mb-4 group-hover:bg-pin-green group-hover:text-white transition-colors">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path v-if="feature.icon === 'database'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2 1 3 3 3h10c2 0 3-1 3-3V7c0-2-1-3-3-3H7C5 4 4 5 4 7z"/>
                                        <path v-else-if="feature.icon === 'users'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2M9 11a4 4 0 100-8 4 4 0 000 8zm13 10v-2a4 4 0 00-3-3.87"/>
                                        <path v-else-if="feature.icon === 'map'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path v-else-if="feature.icon === 'chart'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6m6 0V9a2 2 0 012-2h2a2 2 0 012 2v10m6 0v-4a2 2 0 00-2-2h-2a2 2 0 00-2 2v4"/>
                                        <path v-else-if="feature.icon === 'sprout'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19V6m0 0c0 0-4-4-8-2s0 8 0 8m8-6c0 0 4-4 8-2s0 8 0 8"/>
                                        <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <h3 class="font-semibold text-gray-900 mb-2">{{ feature.title }}</h3>
                                <p class="text-sm text-gray-600">{{ feature.description }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CBC Building Section -->
        <section class="relative min-h-[60vh] flex items-center">
            <div class="absolute inset-0 z-0">
                <img src="/img/bg2.png" alt="DA-CBC Building" class="w-full h-full object-cover object-top" loading="lazy"/>
                <div class="absolute inset-0 bg-black/60"></div>
            </div>
            <div class="relative z-10 section-padding py-20 w-full">
                <div class="container-custom text-center">
                    <p class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white font-display mb-6">{{ $companyName }}</p>
                    <p class="text-lg text-white/80 max-w-3xl mx-auto">
                        The center is a premier hub for innovation and research in agricultural biotechnology. Dedicated to advancing crop productivity and sustainability, the center provides cutting-edge solutions, resources, and technologies to support farmers, researchers, and stakeholders in addressing the challenges of food security and agricultural development.
                    </p>
                </div>
            </div>
        </section>

        <!-- Partners -->
        <section class="py-16 bg-white">
            <div class="section-padding">
                <div class="container-custom">
                    <bm-collaborators />
                </div>
            </div>
        </section>

        <!-- FAQ & Help Section -->
        <section class="py-20 lg:py-32 bg-pin-gray">
            <div class="section-padding">
                <div class="container-custom">
                    <div class="grid lg:grid-cols-2 gap-12 lg:gap-20">
                        <div>
                            <span class="badge-primary mb-4 inline-flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                FAQ
                            </span>
                            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-8 font-display">Frequently Asked Questions</h2>
                            <div class="space-y-3">
                                <div v-for="(item, index) in faqItems" :key="index" class="bg-white rounded-xl shadow-sm overflow-hidden">
                                    <button @click="toggleFaq(index)" class="w-full text-left px-6 py-4 font-medium text-gray-900 hover:text-pin-green flex items-center justify-between transition-colors focus-ring">
                                        <span>{{ item.question }}</span>
                                        <svg :class="['w-5 h-5 transition-transform flex-shrink-0 ml-4', item.open ? 'rotate-180' : '']" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    <transition name="accordion">
                                        <div v-show="item.open" class="px-6 pb-4 text-gray-600">{{ item.answer }}</div>
                                    </transition>
                                </div>
                            </div>
                        </div>
                        <div>
                            <span class="badge-primary mb-4 inline-flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                Contact & Resources
                            </span>
                            <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-8 font-display">Get in Touch</h2>
                            <div class="space-y-6">
                                <div class="grid sm:grid-cols-2 gap-4">
                                    <div class="bg-white rounded-xl p-5 shadow-sm hover:shadow-lg transition-shadow">
                                        <div class="w-10 h-10 bg-pin-green-light rounded-lg flex items-center justify-center text-pin-green mb-3">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        </div>
                                        <h3 class="font-semibold text-gray-900 mb-1">Email Us</h3>
                                        <a href="mailto:cropbiotechcenter@gmail.com" class="text-sm text-pin-green hover:underline">cropbiotechcenter@gmail.com</a>
                                    </div>
                                    <div class="bg-white rounded-xl p-5 shadow-sm hover:shadow-lg transition-shadow">
                                        <div class="w-10 h-10 bg-pin-green-light rounded-lg flex items-center justify-center text-pin-green mb-3">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                        </div>
                                        <h3 class="font-semibold text-gray-900 mb-1">Call Us</h3>
                                        <a href="tel:+639088897135" class="text-sm text-pin-green hover:underline">0908 889 7135</a>
                                    </div>
                                </div>
                                <div class="bg-white rounded-xl p-6 shadow-sm">
                                    <h3 class="font-semibold text-gray-900 mb-4">Quick Links</h3>
                                    <div class="space-y-2">
                                        <Link :href="route('support.terms-of-use')" class="flex items-center justify-between p-3 rounded-lg hover:bg-pin-green-light transition-colors group">
                                            <span class="text-gray-700 group-hover:text-pin-green transition-colors">Terms of Use</span>
                                            <svg class="w-4 h-4 text-gray-400 group-hover:text-pin-green transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </Link>
                                        <Link :href="route('support.privacy-policy')" class="flex items-center justify-between p-3 rounded-lg hover:bg-pin-green-light transition-colors group">
                                            <span class="text-gray-700 group-hover:text-pin-green transition-colors">Privacy Policy</span>
                                            <svg class="w-4 h-4 text-gray-400 group-hover:text-pin-green transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </Link>
                                        <Link :href="route('support.sitemap')" class="flex items-center justify-between p-3 rounded-lg hover:bg-pin-green-light transition-colors group">
                                            <span class="text-gray-700 group-hover:text-pin-green transition-colors">Sitemap</span>
                                            <svg class="w-4 h-4 text-gray-400 group-hover:text-pin-green transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                        </Link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- AI Chat -->
        <section class="py-20 lg:py-32 bg-white">
            <div class="section-padding"><div class="container-custom"><ai-chat/></div></div>
        </section>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white">
            <div class="section-padding py-16">
                <div class="container-custom">
                    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-10">
                        <div class="sm:col-span-2 lg:col-span-1">
                            <Link href="/" class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                                    <img src="/img/logos/pin.svg" alt="PIN" class="w-6 h-6"/>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-400">DA - Crop Biotechnology Center</p>
                                    <p class="text-sm font-bold font-display">PIN System</p>
                                </div>
                            </Link>
                            <p class="text-gray-400 text-sm mb-6">Empowering crop biotechnology research with innovation, one discovery at a time.</p>
                            <div class="flex items-center gap-3">
                                <a href="https://www.facebook.com/DACropBiotechCenter" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-pin-green transition-colors">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </a>
                                <a href="https://dacbc.philrice.gov.ph" target="_blank" rel="noopener noreferrer" class="w-10 h-10 bg-white/10 rounded-lg flex items-center justify-center hover:bg-pin-green transition-colors">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>
                                </a>
                            </div>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg mb-4">Quick Links</h3>
                            <ul class="space-y-3">
                                <li><Link href="/" class="text-gray-400 hover:text-white transition-colors">Home</Link></li>
                                <li v-for="project in Object.values(CBCProjectsPublic)" :key="project.id"><Link :href="route(project.route_public)" class="text-gray-400 hover:text-white transition-colors">{{ project.label }}</Link></li>
                                <li><Link :href="route('support.what-is-pin')" class="text-gray-400 hover:text-white transition-colors">About PIN</Link></li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg mb-4">Resources</h3>
                            <ul class="space-y-3">
                                <li><Link :href="route('support.terms-of-use')" class="text-gray-400 hover:text-white transition-colors">Terms of Use</Link></li>
                                <li><Link :href="route('support.privacy-policy')" class="text-gray-400 hover:text-white transition-colors">Privacy Policy</Link></li>
                                
                                <li><Link :href="route('support.sitemap')" class="text-gray-400 hover:text-white transition-colors">Sitemap</Link></li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg mb-4">Contact Us</h3>
                            <div class="space-y-4 text-sm text-gray-400">
                                <p>PhilRice Compound, Maligaya<br/>Science City of Muñoz<br/>Nueva Ecija, Philippines 3119</p>
                                <p><a href="tel:+639088897135" class="hover:text-white transition-colors">Mobile: 0908 889 7135</a></p>
                                <p><a href="mailto:cropbiotechcenter@gmail.com" class="hover:text-white transition-colors">cropbiotechcenter@gmail.com</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-t border-white/10">
                <div class="section-padding py-6">
                    <div class="container-custom">
                        <div class="flex flex-wrap items-center justify-center gap-x-6 gap-y-2 text-xs text-gray-500">
                            <span class="font-medium text-gray-400">Republic of the Philippines:</span>
                            <a href="https://www.officialgazette.gov.ph/" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors">Official Gazette</a>
                            <a href="https://op-proper.gov.ph/" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors">Office of the President</a>
                            <a href="https://www.da.gov.ph/" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors">Department of Agriculture</a>
                            <a href="https://www.dost.gov.ph/" target="_blank" rel="noopener noreferrer" class="hover:text-white transition-colors">DOST</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="border-t border-white/10">
                <div class="section-padding py-6">
                    <div class="container-custom text-center text-sm text-gray-500">
                        <p>&copy; {{ new Date().getFullYear() }} DA - Crop Biotechnology Center. All rights reserved.</p>
                        <p class="mt-1">Plant Breeders and Innovators Network System</p>
                    </div>
                </div>
            </div>
        </footer>
    </page-layout>
</template>

<style scoped>
.accordion-enter-active,
.accordion-leave-active {
    transition: all 0.3s ease;
    max-height: 500px;
    overflow: hidden;
}
.accordion-enter-from,
.accordion-leave-to {
    max-height: 0;
    opacity: 0;
}
.line-clamp-3 {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
