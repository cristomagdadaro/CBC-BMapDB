<script>
export default {
    name: "BmCollaborators",
    computed: {
        visibleCollaborators() {
            return this.collaborators.filter(collaborator => collaborator.visible);
        },
        marqueeCollaborators() {
            return [...this.visibleCollaborators, ...this.visibleCollaborators];
        }
    },
    emits: ['collaboratorsListReady'],
    mounted() {
        this.$emit('collaboratorsListReady', this.collaborators);
    },
    data() {
        return {
            logo_dir: "/img/collaborators/",
            collaborators: [
                {
                    name: "Philippine Rice Research Institute (PhilRice)",
                    link: "/",
                    logo: "PhilRice.png",
                    visible: true,
                },
                {
                    name: "DA - Biotechnology Program Office (DA-BPO)",
                    link: "/",
                    logo: "DA-BPO.png",
                    visible: true,
                },
                {
                    name: "DA - Regional Field Office 3 (Central Luzon)",
                    link: "/",
                    logo: "DA-RFO-III.png",
                    visible: true,
                },
                {
                    name: "DA - Regional Field Office 1 (La Union)",
                    link: "/",
                    logo: "DA-RFO-I.png",
                    visible: true,
                },
                {
                    name: "BPI - National Seed Quality Control Services (BPI-NSQCS) Region 3",
                    link: "/",
                    logo: "BPI-NSQCS.png",
                    visible: true,
                },
                {
                    name: "Regional Crop Protection Center (DA-RCPC) Region 3",
                    link: "/",
                    logo: "DA-RCPC-3.png",
                    visible: true,
                },
                {
                    name: "Central Luzon State University (CLSU)",
                    link: "/",
                    logo: "CLSU.png",
                    visible: true,
                },
                {
                    name: "Aurora State College of Technology (ASCOT)",
                    link: "/",
                    logo: "ASCOT.png",
                    visible: true,
                },
                {
                    name: "Tarlac Agricultural University (TAU)",
                    link: "/",
                    logo: "TAU.png",
                    visible: false,
                },
                {
                    name: "Nueva Ecija University of Science and Technology (NEUST) Gabaldon",
                    link: "/",
                    logo: "NEUST.png",
                    visible: false,
                },
                {
                    name: "Bulacan State University (BulSU)",
                    link: "/",
                    logo: "BulSU.png",
                    visible: true,
                },
                {
                    name: "Pangasinan State University (PSU)",
                    link: "/",
                    logo: "PSU.png",
                    visible: true,
                },
                {
                    name: "Mariano Marcos State University (MMSU)",
                    link: "/",
                    logo: "MMSU.png",
                    visible: true,
                },
                {
                    name: "Visayas State University (VSU)",
                    link: "/",
                    logo: "VSU.png",
                    visible: true,
                },
            ]
        }
    }
}
</script>

<template>
    <section class="py-8 lg:py-10" aria-labelledby="partners-heading">
        <div class="text-center mb-8 lg:mb-10">
            <span class="badge-primary mb-4 inline-flex items-center gap-1">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                Our Partners
            </span>
            <h2 id="partners-heading" class="text-2xl sm:text-3xl font-bold text-gray-900 font-display">Collaborating with Leading Institutions</h2>
            <p class="text-gray-600 mt-2">Trusted collaborators helping advance crop biotechnology in the Philippines.</p>
        </div>

        <div class="relative overflow-hidden" aria-label="Collaborators carousel">
            <div class="absolute left-0 top-0 bottom-0 w-24 sm:w-32 bg-gradient-to-r from-white to-transparent z-10" aria-hidden="true" />
            <div class="absolute right-0 top-0 bottom-0 w-24 sm:w-32 bg-gradient-to-l from-white to-transparent z-10" aria-hidden="true" />

            <div class="flex w-max animate-marquee hover:[animation-play-state:paused]">
                <div
                    v-for="(collaborator, index) in marqueeCollaborators"
                    :key="`${collaborator.name}-${index}`"
                    class="flex-shrink-0 mx-2 sm:mx-3 lg:mx-4 bg-white rounded-xl shadow-sm hover:shadow-card transition-all duration-300 hover:-translate-y-1 p-4 lg:p-5"
                >
                    <a :href="collaborator.link" target="_blank" rel="noopener noreferrer" class="w-36 sm:w-40 lg:w-44 h-16 sm:h-20 lg:h-24 flex items-center justify-center focus-ring rounded-lg">
                        <img
                            :src="logo_dir + collaborator.logo"
                            :alt="collaborator.name"
                            class="w-full h-full object-contain"
                            loading="lazy"
                        />
                    </a>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>

</style>
