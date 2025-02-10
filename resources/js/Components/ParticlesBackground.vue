<script>
export default {
    name: 'ParticlesBackground',
    props: {
        id: {
            type: String,
            default: "particles-js",
        },
        configPath: {
            type: String,
            default: "/particlesjs-config.json",
        }
    },
    data() {
        return {
            particlesConfig: null,
        };
    },
    async mounted() {
        await this.loadParticlesConfig();
        this.setupParticles();
    },
    methods: {
        async loadParticlesConfig() {
            if (!this.particlesConfig) {
                const response = await fetch(this.configPath);
                this.particlesConfig = await response.json();
            }
        },
        setupParticles() {
            if (this.particlesConfig) {
                particlesJS(this.id, this.particlesConfig);
            }
        }
    }
};
</script>

<template>
    <div :id="id" class="particles-container"></div>
</template>

<style scoped>
.particles-container {
    position: absolute;
    width: 100%;
    height: 100%;
    z-index: -1;
}
</style>
