<script>
export default {
    name: "PublicPageSection",
    props: {
        animation:{
            type: Boolean,
            default: true
        }
    },
    mounted() {
        if (this.animation){
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('slide-up');
                        entry.target.classList.remove('slide-down');
                    } else {
                        entry.target.classList.add('slide-down');
                        entry.target.classList.remove('slide-up');
                    }
                });
            }, { threshold: 0.1 });

            document.querySelectorAll('.public-page-section').forEach(section => {
                observer.observe(section);
            });
        }
    }
}
</script>

<template>
    <div class="z-10 relative">
        <slot name="custom-bg" />
        <div v-if="animation" class="public-page-section resp-container py-8 w-full">
            <slot />
        </div>
        <div v-else class="resp-container">
            <slot />
        </div>
    </div>
</template>

<style scoped>
.public-page-section {
    opacity: 0;
    transform: translateY(20px);
    transition: opacity 1.5s ease-out, transform 2s ease-out;
}

.public-page-section.slide-up {
    opacity: 1;
    transform: translateY(0);
}

.public-page-section.slide-down {
    opacity: 0;
    transform: translateY(20px);
}
</style>
