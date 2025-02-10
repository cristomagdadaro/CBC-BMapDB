<template>
    <div ref="canvasContainer" class="model-container"></div>
</template>

<script>
import * as THREE from 'three';
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls'
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js';

export default {
    name: "ModelViewer",
    props: {
        modelUrl: String,
    },
    mounted() {
        this.initThreeJS();
    },

    beforeDestroy() {
        this.cleanup();
    },

    methods: {
        initThreeJS() {
            // Scene setup
            this.scene = new THREE.Scene();
            this.camera = new THREE.PerspectiveCamera(25,
                this.$refs.canvasContainer.offsetWidth / this.$refs.canvasContainer.offsetHeight,
                0.1,
                200
            );

            // Renderer
            this.renderer = new THREE.WebGLRenderer({ antialias: true });
            this.renderer.setSize(
                this.$refs.canvasContainer.offsetWidth,
                this.$refs.canvasContainer.offsetHeight
            );
            this.$refs.canvasContainer.appendChild(this.renderer.domElement);
            this.renderer.shadowMap.enabled = true;
            this.renderer.shadowMap.type = THREE.PCFSoftShadowMap;

            // Add ground plane
            this.addGround();

            // Add grass
            this.addGrass();

            const envTexture = new THREE.CubeTextureLoader()
                .setPath('/img/3d/')
                .load(['bg.jpg']);

            this.scene.environment = envTexture;
            this.scene.background = envTexture;

            // Lighting
            const ambientLight = new THREE.AmbientLight(0xffffff, 2);
            this.scene.add(ambientLight);

            const directionalLight = new THREE.DirectionalLight(0xffffff, 30);
            directionalLight.position.set(0, 70, 20);
            directionalLight.castShadow = true;
            this.scene.add(directionalLight);

            // Controls
            this.controls = new OrbitControls(this.camera, this.renderer.domElement);
            this.controls.enableRotate = true; // Disable rotation
            this.controls.enableZoom = true; // Disable zoom
            this.controls.enablePan = false; // Enable panning

            this.controls.minDistance = 5;
            this.controls.maxDistance = 50;

            // Restrict rotation to the y-axis only
            this.controls.minPolarAngle = Math.PI / 2; // Lock vertical rotation (90 degrees)
            this.controls.maxPolarAngle = Math.PI / 2; // Lock vertical rotation (90 degrees)

            // Optional: Set rotation speed
            this.controls.rotateSpeed = 0.4; // Adjust rotation speed

            this.camera.position.set(0, 0, 30);
            this.camera.lookAt(0, 0, 0);

            // Load model
            const loader = new GLTFLoader();
            loader.load(
                this.modelUrl,
                (gltf) => {
                    this.model = gltf.scene;
                    this.scene.add(this.model);
                    this.animate();
                    // Center the model
                    const box = new THREE.Box3().setFromObject(this.model);
                    const center = new THREE.Vector3();
                    box.getCenter(center);
                    this.model.position.sub(center);

                    // Enable shadow casting for the model and ground
                    this.model.traverse((child) => {
                        if (child.isMesh) {
                            child.castShadow = true;
                            child.receiveShadow = true;
                        }
                    });
                },
                undefined,
                (error) => {
                    console.error('Error loading model:', error);
                }
            );

            // Handle window resize
            window.addEventListener('resize', this.onWindowResize);
        },

        addGround() {
            const textureLoader = new THREE.TextureLoader();
            const groundTexture = textureLoader.load('/img/3d/grass.png');
            groundTexture.wrapS = groundTexture.wrapT = THREE.RepeatWrapping;
            groundTexture.repeat.set(1, 1);
            groundTexture.generateMipmaps = true;

            const groundGeometry = new THREE.PlaneGeometry(50, 50); // Ground size
            const groundMaterial = new THREE.MeshStandardMaterial({
                //map: groundTexture,
                color: 0xE5E7EB,
                side: THREE.DoubleSide,
            });
            const ground = new THREE.Mesh(groundGeometry, groundMaterial);
            ground.rotation.x = -Math.PI / 2; // Rotate to be horizontal
            ground.position.y = -5; // Adjust height
            ground.receiveShadow = true;

            this.scene.add(ground);
        },
        addGrass() {
            // Load grass texture
            const textureLoader = new THREE.TextureLoader();
            const grassTexture = textureLoader.load('/img/3d/grass.png'); // Path to your grass texture
            grassTexture.wrapS = THREE.RepeatWrapping;
            grassTexture.wrapT = THREE.RepeatWrapping;

            // Grass blade geometry
            const grassBladeGeometry = new THREE.PlaneGeometry(0.1, 0.6, 1, 4); // Single grass blade

            // Grass material with texture
            const grassMaterial = new THREE.MeshStandardMaterial({
                map: grassTexture, // Use the grass texture
                alphaMap: grassTexture, // Use the alpha channel for transparency
                transparent: false,
                side: THREE.DoubleSide,
            });

            // Create instanced mesh for grass
            const grassCount = 50000; // Number of grass blades
            const grassMesh = new THREE.InstancedMesh(grassBladeGeometry, grassMaterial, grassCount);

            // Randomize grass positions and rotations
            const dummy = new THREE.Object3D();
            for (let i = 0; i < grassCount; i++) {
                const x = (Math.random() - 0.5) * 50; // Spread across the ground
                const z = (Math.random() - 0.5) * 50;
                const y = -1; // Ground level

                dummy.position.set(x, y, z);
                dummy.rotation.y = Math.random() * Math.PI * 2; // Random rotation
                dummy.scale.setScalar(0.5 + Math.random() * 0.5); // Random scale
                dummy.updateMatrix();
                grassMesh.setMatrixAt(i, dummy.matrix);
            }

            grassMesh.instanceMatrix.needsUpdate = true;
            grassMesh.position.y = -3.6;
            grassMesh.castShadow = true; // Grass casts shadow
            grassMesh.receiveShadow = true; // Grass receives shadow
            this.scene.add(grassMesh);
        },
        animate() {
            this.animationFrame = requestAnimationFrame(this.animate);

            // Rotate the model infinitely
            if (this.model) {
                this.model.rotation.y += 0.002; // Adjust rotation speed
            }

            this.controls.update(); // Required for OrbitControls
            this.renderer.render(this.scene, this.camera);
        },

        onWindowResize() {
            this.camera.aspect =
                this.$refs.canvasContainer.offsetWidth / this.$refs.canvasContainer.offsetHeight;
            this.camera.updateProjectionMatrix();
            this.renderer.setSize(
                this.$refs.canvasContainer.offsetWidth,
                this.$refs.canvasContainer.offsetHeight
            );
        },

        cleanup() {
            window.removeEventListener('resize', this.onWindowResize);
            this.$refs.canvasContainer.removeChild(this.renderer.domElement);
            cancelAnimationFrame(this.animationFrame);
            this.model = null;
        }
    }
}
</script>

<style>
.model-container {
    width: 100%;
    height: 700px;
}
</style>
