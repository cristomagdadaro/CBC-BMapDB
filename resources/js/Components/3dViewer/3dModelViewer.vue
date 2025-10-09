<template>
    <div ref="canvasContainer" class="model-container"></div>
</template>

<script>
import * as THREE from 'three';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls'
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js';
import { RGBELoader } from 'three/examples/jsm/loaders/RGBELoader.js';

export default {
    name: "ModelViewer",
    props: {
        modelUrl: { type: String, required: true },
        autoRotate: { type: Boolean, default: true },
        lightenOnZoom: { type: Boolean, default: true },
        baseExposure: { type: Number, default: 1.15 },
        closeBoost: { type: Number, default: 0.25 },        // extra exposure added when very close
        farReduction: { type: Number, default: 0.30 },      // how much exposure is reduced when far
        exposureSmoothing: { type: Number, default: 0.18 }, // 0..1 lerp factor for smoother changes
        minExposure: { type: Number, default: 0.9 },        // clamp floor for exposure
        maxExposure: { type: Number, default: 1.7 },        // clamp ceiling for exposure
        environmentHdr: { type: String, default: '' },      // optional .hdr file path
        envIntensity: { type: Number, default: 1.0 },       // multiplier for material envMapIntensity
        useHdrAsBackground: { type: Boolean, default: true }
    },
    data() {
        return {
            lastExposure: null,
        }
    },
    mounted() {
        this.initThreeJS();
    },
    beforeDestroy() {
        this.cleanup();
    },
    methods: {
        initThreeJS() {
            // Scene & Camera
            this.scene = new THREE.Scene();
            this.scene.background = new THREE.Color('#cfe8ff');
            this.camera = new THREE.PerspectiveCamera(
                30,
                this.$refs.canvasContainer.offsetWidth / this.$refs.canvasContainer.offsetHeight,
                0.1,
                500
            );

            // Renderer
            this.renderer = new THREE.WebGLRenderer({ antialias: true, alpha: false });
            this.renderer.physicallyCorrectLights = true;
            this.renderer.setPixelRatio(window.devicePixelRatio);
            this.renderer.setSize(
                this.$refs.canvasContainer.offsetWidth,
                this.$refs.canvasContainer.offsetHeight
            );
            if (this.renderer.outputColorSpace !== undefined) {
                this.renderer.outputColorSpace = THREE.SRGBColorSpace;
            } else {
                this.renderer.outputEncoding = THREE.sRGBEncoding;
            }
            this.renderer.toneMapping = THREE.ACESFilmicToneMapping;
            this.renderer.toneMappingExposure = this.baseExposure;
            this.lastExposure = this.baseExposure;
            this.renderer.shadowMap.enabled = true;
            this.renderer.shadowMap.type = THREE.PCFSoftShadowMap;
            this.$refs.canvasContainer.appendChild(this.renderer.domElement);

            // Ground & Grass
            this.addGround();
            this.addGrass(1200);

            // Optional environment HDR (improves reflections & overall brightness realism)
            this.loadEnvironment();

            // Lights
            this.addLights();

            // Controls
            this.controls = new OrbitControls(this.camera, this.renderer.domElement);
            this.controls.enablePan = false;
            this.controls.minDistance = 3;
            this.controls.maxDistance = 60;
            this.controls.minPolarAngle = Math.PI / 3.2;
            this.controls.maxPolarAngle = Math.PI / 1.3;
            this._controlsChangeHandler = this.onControlsChange.bind(this);
            this.controls.addEventListener('change', this._controlsChangeHandler);

            this.camera.position.set(0, 4, 28);
            this.camera.lookAt(0, 0, 0);

            // Load model
            const loader = new GLTFLoader();
            loader.load(
                this.modelUrl,
                (gltf) => {
                    this.model = gltf.scene;
                    const box = new THREE.Box3().setFromObject(this.model);
                    const size = new THREE.Vector3();
                    box.getSize(size);
                    const center = new THREE.Vector3();
                    box.getCenter(center);
                    this.model.position.sub(center);
                    this.modelCenter = new THREE.Vector3(0,0,0); // after centering
                    const maxDim = Math.max(size.x, size.y, size.z);
                    const targetSize = 12;
                    const scale = targetSize / maxDim;
                    this.model.scale.setScalar(scale);
                    this.model.traverse(obj => {
                        if (obj.isMesh) {
                            obj.castShadow = true;
                            obj.receiveShadow = true;
                            if (obj.material) {
                                if ('envMapIntensity' in obj.material) obj.material.envMapIntensity = 1.25;
                                obj.material.needsUpdate = true;
                            }
                        }
                    });
                    this.scene.add(this.model);
                    this.onControlsChange();
                    // Re-apply env intensity if HDR was loaded after model
                    this.applyEnvIntensity(this.model);
                    this.startAnimation();
                },
                undefined,
                (err) => console.error('Error loading model:', err)
            );

            window.addEventListener('resize', this.onWindowResize);
        },
        addLights() {
            if (this.keyLight) {
                [this.keyLight, this.fillLight, this.rimLight, this.hemiLight, this.ambientLight].forEach(l => l && this.scene.remove(l));
            }
            this.hemiLight = new THREE.HemisphereLight(0xe0f3ff, 0x4b5b34, 0.9);
            this.scene.add(this.hemiLight);
            this.ambientLight = new THREE.AmbientLight(0xffffff, 0.36);
            this.scene.add(this.ambientLight);
            this.keyLight = new THREE.DirectionalLight(0xffffff, 2.3);
            this.keyLight.position.set(15, 25, 18);
            this.configureShadow(this.keyLight, 50);
            this.scene.add(this.keyLight);
            this.fillLight = new THREE.DirectionalLight(0xcfdfff, 1.2);
            this.fillLight.position.set(-18, 15, -10);
            this.configureShadow(this.fillLight, 30, true);
            this.scene.add(this.fillLight);
            this.rimLight = new THREE.DirectionalLight(0xffffff, 1.35);
            this.rimLight.position.set(0, 18, -25);
            this.configureShadow(this.rimLight, 20, true);
            this.scene.add(this.rimLight);
        },
        configureShadow(light, range = 30, disable = false) {
            if (disable) { light.castShadow = false; return; }
            light.castShadow = true;
            const cam = light.shadow.camera;
            cam.near = 1;
            cam.far = 120;
            cam.left = cam.bottom = -range;
            cam.right = cam.top = range;
            light.shadow.mapSize.set(1024, 1024);
            light.shadow.bias = -0.00015;
        },
        addGround() {
            const groundGeometry = new THREE.PlaneGeometry(200, 200);
            const groundMaterial = new THREE.MeshStandardMaterial({ color: 0xe2e8e4, roughness: 0.85, metalness: 0.0 });
            const ground = new THREE.Mesh(groundGeometry, groundMaterial);
            ground.rotation.x = -Math.PI / 2;
            ground.position.y = -6;
            ground.receiveShadow = true;
            this.scene.add(ground);
        },
        addGrass(count = 800) {
            const textureLoader = new THREE.TextureLoader();
            textureLoader.load('/img/3d/grass.png', (grassTexture) => {
                grassTexture.wrapS = grassTexture.wrapT = THREE.ClampToEdgeWrapping;
                const bladeGeo = new THREE.PlaneGeometry(0.15, 0.9, 1, 3);
                const mat = new THREE.MeshStandardMaterial({
                    map: grassTexture,
                    alphaMap: grassTexture,
                    transparent: true,
                    side: THREE.DoubleSide,
                    depthWrite: false
                });
                const mesh = new THREE.InstancedMesh(bladeGeo, mat, count);
                const dummy = new THREE.Object3D();
                for (let i = 0; i < count; i++) {
                    const r = 35 * Math.sqrt(Math.random());
                    const theta = Math.random() * Math.PI * 2;
                    const x = Math.cos(theta) * r;
                    const z = Math.sin(theta) * r;
                    dummy.position.set(x, -5.9, z);
                    dummy.rotation.y = Math.random() * Math.PI * 2;
                    dummy.rotation.z = (Math.random() - 0.5) * 0.25;
                    const s = 0.6 + Math.random() * 0.7;
                    dummy.scale.setScalar(s);
                    dummy.updateMatrix();
                    mesh.setMatrixAt(i, dummy.matrix);
                }
                mesh.instanceMatrix.needsUpdate = true;
                mesh.receiveShadow = true;
                this.scene.add(mesh);
            });
        },
        startAnimation() {
            if (this.animating) return;
            this.animating = true;
            this.animate = () => {
                this.animationFrame = requestAnimationFrame(this.animate);
                if (this.model && this.autoRotate) {
                    this.model.rotation.y += 0.0025;
                }
                this.controls.update();
                this.renderer.render(this.scene, this.camera);
            };
            this.animate();
        },
        loadEnvironment() {
            if (!this.environmentHdr) return;
            new RGBELoader().load(this.environmentHdr, (hdrTex) => {
                hdrTex.mapping = THREE.EquirectangularReflectionMapping;
                this.scene.environment = hdrTex;
                if (this.useHdrAsBackground) this.scene.background = hdrTex;
                // apply to existing model if loaded
                if (this.model) this.applyEnvIntensity(this.model);
            }, undefined, (e) => {
                console.warn('Failed to load HDR environment:', e);
            });
        },
        applyEnvIntensity(root) {
            if (!root) return;
            root.traverse(o => {
                if (o.isMesh && o.material) {
                    const mats = Array.isArray(o.material) ? o.material : [o.material];
                    mats.forEach(m => {
                        if ('envMapIntensity' in m) {
                            // base value (fallback 1) scaled by envIntensity prop
                            const base = m.envMapIntensity || 1;
                            m.envMapIntensity = base * this.envIntensity;
                            m.needsUpdate = true;
                        }
                    });
                }
            });
        },
        onControlsChange() {
            if (!this.lightenOnZoom) return;
            const dist = this.camera.position.distanceTo(this.modelCenter || new THREE.Vector3(0,0,0));
            const t = THREE.MathUtils.clamp((dist - this.controls.minDistance) / (this.controls.maxDistance - this.controls.minDistance), 0, 1);
            // Raw target exposure based on distance interpolation
            const rawTarget = (this.baseExposure + this.closeBoost) - t * this.farReduction;
            const targetExposure = THREE.MathUtils.clamp(rawTarget, this.minExposure, this.maxExposure);
            const lerp = this.exposureSmoothing;
            const newExposure = this.lastExposure + (targetExposure - this.lastExposure) * lerp;
            this.renderer.toneMappingExposure = newExposure;
            this.lastExposure = newExposure;
            // Light intensities also adapt (smoothed implicitly by distance changes)
            if (this.fillLight) this.fillLight.intensity = 1.0 + (1 - t) * 0.75; // up to 1.75 near
            if (this.keyLight) this.keyLight.intensity = 2.1 + (1 - t);  // up to 3.1 near
            if (this.ambientLight) this.ambientLight.intensity = 0.38 + (1 - t) * 0.22; // 0.60 near -> 0.38 far
            if (this.hemiLight) this.hemiLight.intensity = 0.95 + (1 - t) * 0.18; // 1.13 near -> 0.95 far
        },
        onWindowResize: function() {
            if (!this.camera || !this.renderer) return;
            this.camera.aspect = this.$refs.canvasContainer.offsetWidth / this.$refs.canvasContainer.offsetHeight;
            this.camera.updateProjectionMatrix();
            this.renderer.setSize(
                this.$refs.canvasContainer.offsetWidth,
                this.$refs.canvasContainer.offsetHeight
            );
        },
        cleanup() {
            window.removeEventListener('resize', this.onWindowResize);
            if (this.controls && this._controlsChangeHandler) this.controls.removeEventListener('change', this._controlsChangeHandler);
            cancelAnimationFrame(this.animationFrame);
            if (this.renderer && this.renderer.domElement && this.$refs.canvasContainer.contains(this.renderer.domElement)) {
                this.$refs.canvasContainer.removeChild(this.renderer.domElement);
            }
            this.scene && this.scene.traverse(obj => {
                if (obj.isMesh) {
                    if (obj.geometry) obj.geometry.dispose();
                    if (obj.material) {
                        if (Array.isArray(obj.material)) obj.material.forEach(m => m.dispose()); else obj.material.dispose();
                    }
                }
            });
            this.renderer && this.renderer.dispose();
            this.scene = null; this.camera = null; this.renderer = null;
            this.model = null; this.controls = null; this.animating = false;
        }
    }
}
</script>

<style>
.model-container { width: 100%; height: 700px; position: relative; }
</style>
