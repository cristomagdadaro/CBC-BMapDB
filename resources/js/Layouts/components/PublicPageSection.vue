<script>
export default {
    name: "PublicPageSection",
    props: {
        animation: {
            type: Boolean,
            default: true
        },
        fullHeight: {
            type: Boolean,
            default: true
        },
        bgClass: {
            type: String,
            default: ''
        }
    },
    mounted() {
        if (this.animation) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('fade-up-visible');
                        entry.target.classList.remove('fade-up-hidden');
                    }
                });
            }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

            this.$el.querySelectorAll('.public-page-section').forEach(section => {
                observer.observe(section);
            });
        }
    }
}
</script>

<template>
    <div :class="['relative', fullHeight ? 'min-h-screen' : '', bgClass]">
        <slot name="custom-bg" />
        <div v-if="animation" class="public-page-section fade-up-hidden section-padding py-12 lg:py-20 w-full">
            <div class="container-custom">
                <slot />
            </div>
        </div>
        <div v-else class="section-padding py-12 lg:py-20">
            <div class="container-custom">
                <slot />
            </div>
        </div>
    </div>
</template>

<style scoped>
.public-page-section {
    transition: opacity 0.8s ease-out, transform 0.8s ease-out;
}

.fade-up-hidden {
    opacity: 0;
    transform: translateY(30px);
}

.fade-up-visible {
    opacity: 1;
    transform: translateY(0);
}
</style>
