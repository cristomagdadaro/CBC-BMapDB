import BaseCreateForm from "@/Components/Modal/BaseCreateForm.vue";
import TextField from "@/Components/Form/TextField.vue";
import SelectField from "@/Components/Form/SelectField.vue";
import SelectSearchField from "@/Components/Form/SelectSearchField.vue";
import SelectSearchFieldV2 from "@/Components/Form/SelectSearchFieldV2.vue";
import BaseEditForm from "@/Components/Modal/BaseEditForm.vue";
import RadioField from "@/Components/Form/RadioField.vue";
import CancelButton from "@/Components/CRCMDatatable/Components/CancelButton.vue";
import CloseIcon from "@/Components/Icons/CloseIcon.vue";
import BaseClass from "@/Modules/core/domain/base/BaseClass";
import User from "@/Modules/core/domain/auth/User";
import ApiService from "@/Modules/core/infrastructure/ApiService";

export default {
    components: {
        BaseCreateForm,
        BaseEditForm,
        TextField,
        SelectField,
        RadioField,
        SelectSearchField,
        SelectSearchFieldV2,
        CancelButton,
        CloseIcon,
    },
    data() {
        return {
            form: {},
            model: BaseClass,
        };
    },
    props: {
        errors: {
            type: Object,
            default: null
        },
        forceClose: {
            type: Boolean,
            default: false
        },
        data: {
            type: Object,
            default: null
        },
        id: {
            type: Number,
            default: null
        },
        processing: {
            type: Boolean,
            default: false
        }
    },
    methods: {
        resetForm() {
            this.form = this.model.updateForm(this.data); // Get only fields needed for update
        },
        getTitle(name) {
            if (this.data)
                return this.model.getUpdateFieldTitle(name);
            else
                return this.model.getCreateFieldTitle(name);
        },
        getError(name) {
            return this.errors ? this.errors[name] : null;
        },
        close() {
            this.form.map((key, value) => {
                this.form[key] = null;
            });

            this.emitClose();
        },
        emitClose() {
            this.$emit('close');
        },
        isAdmin(){
            return (new this.User(this.$page.props.auth.user)).isAdmin;
        },
        async getCustomSelectionOptions(url){
            return await (new ApiService(url)).get({}, null);
        },

        /**
         * Enhanced image compression with multiple format support and progress tracking
         * @param {File|string} imageInput - File object or data URL
         * @param {Object} options - Compression options
         * @returns {Promise<string>} - Compressed image data URL
         */
        async compressImage(imageInput, options = {}) {
            const defaultOptions = {
                maxWidth: 960,
                maxHeight: 540,
                quality: 0.8,
                maxSizeBytes: 2 * 1024 * 1024, // 2MB
                format: 'image/jpeg',
                maintainAspectRatio: true,
                onProgress: null // Callback for progress updates
            };

            const config = { ...defaultOptions, ...options };

            return new Promise((resolve, reject) => {
                try {
                    const img = new Image();

                    img.onload = () => {
                        try {
                            const canvas = document.createElement('canvas');
                            const ctx = canvas.getContext('2d');

                            // Calculate dimensions while maintaining aspect ratio
                            let { width, height } = this.calculateDimensions(
                                img.width,
                                img.height,
                                config.maxWidth,
                                config.maxHeight,
                                config.maintainAspectRatio
                            );

                            canvas.width = width;
                            canvas.height = height;

                            // Apply image smoothing for better quality
                            ctx.imageSmoothingEnabled = true;
                            ctx.imageSmoothingQuality = 'high';

                            // Draw and compress image
                            ctx.drawImage(img, 0, 0, width, height);

                            if (config.onProgress) {
                                config.onProgress(50); // 50% - Image drawn
                            }

                            this.optimizeImageQuality(canvas, config, (compressedDataUrl) => {
                                if (config.onProgress) {
                                    config.onProgress(100); // 100% - Compression complete
                                }
                                resolve(compressedDataUrl);
                            });

                        } catch (error) {
                            reject(new Error(`Canvas processing failed: ${error.message}`));
                        }
                    };

                    img.onerror = () => {
                        reject(new Error('Failed to load image'));
                    };

                    // Handle different input types
                    if (imageInput instanceof File) {
                        if (config.onProgress) {
                            config.onProgress(10); // 10% - Starting file read
                        }

                        const reader = new FileReader();
                        reader.onload = (e) => {
                            if (config.onProgress) {
                                config.onProgress(25); // 25% - File read complete
                            }
                            img.src = e.target.result;
                        };
                        reader.onerror = () => reject(new Error('Failed to read file'));
                        reader.readAsDataURL(imageInput);
                    } else if (typeof imageInput === 'string') {
                        if (config.onProgress) {
                            config.onProgress(25); // 25% - Using existing data URL
                        }
                        img.src = imageInput;
                    } else {
                        reject(new Error('Invalid image input type'));
                    }

                } catch (error) {
                    reject(new Error(`Image compression failed: ${error.message}`));
                }
            });
        },

        /**
         * Calculate optimal dimensions while maintaining aspect ratio
         */
        calculateDimensions(originalWidth, originalHeight, maxWidth, maxHeight, maintainAspectRatio = true) {
            if (!maintainAspectRatio) {
                return { width: maxWidth, height: maxHeight };
            }

            const aspectRatio = originalWidth / originalHeight;
            let width = originalWidth;
            let height = originalHeight;

            // Scale down if dimensions exceed maximums
            if (width > maxWidth) {
                width = maxWidth;
                height = width / aspectRatio;
            }

            if (height > maxHeight) {
                height = maxHeight;
                width = height * aspectRatio;
            }

            return {
                width: Math.round(width),
                height: Math.round(height)
            };
        },

        /**
         * Optimize image quality to meet size requirements
         */
        optimizeImageQuality(canvas, config, callback) {
            let quality = config.quality;
            let attempts = 0;
            const maxAttempts = 20;

            const compress = () => {
                const compressedDataUrl = canvas.toDataURL(config.format, quality);
                const byteSize = this.getDataUrlSize(compressedDataUrl);

                console.log(`Compression attempt ${attempts + 1}: ${(byteSize / 1024 / 1024).toFixed(2)}MB at ${(quality * 100).toFixed(0)}% quality`);

                if (byteSize <= config.maxSizeBytes || quality <= 0.1 || attempts >= maxAttempts) {
                    callback(compressedDataUrl);
                    return;
                }

                // Reduce quality more aggressively as attempts increase
                const reductionFactor = attempts < 5 ? 0.05 : 0.1;
                quality = Math.max(0.1, quality - reductionFactor);
                attempts++;

                // Use setTimeout to prevent blocking UI
                setTimeout(compress, 10);
            };

            compress();
        },

        /**
         * Calculate data URL size in bytes
         */
        getDataUrlSize(dataUrl) {
            const base64String = dataUrl.split(',')[1];
            return Math.ceil(base64String.length * 3 / 4);
        },

        /**
         * Enhanced resize function with better error handling (legacy support)
         * @deprecated Use compressImage instead
         */
        resizedataURL(datas, wantedWidth, wantedHeight, callback) {
            console.warn('resizedataURL is deprecated. Use compressImage instead.');

            this.compressImage(datas, {
                maxWidth: wantedWidth,
                maxHeight: wantedHeight,
                maintainAspectRatio: false
            }).then(callback).catch(error => {
                console.error('Image compression failed:', error);
                callback(datas); // Fallback to original
            });
        },

        /**
         * Validate image file before compression
         */
        validateImageFile(file, maxSizeMB = 50) {
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
            const maxBytes = maxSizeMB * 1024 * 1024;

            if (!file) {
                throw new Error('No file provided');
            }

            if (!validTypes.includes(file.type)) {
                throw new Error(`Invalid file type. Supported types: ${validTypes.join(', ')}`);
            }

            if (file.size > maxBytes) {
                throw new Error(`File too large. Maximum size: ${maxSizeMB}MB`);
            }

            return true;
        },

        /**
         * Batch compress multiple images
         */
        async compressMultipleImages(files, options = {}) {
            const results = [];
            const totalFiles = files.length;

            for (let i = 0; i < files.length; i++) {
                const file = files[i];

                try {
                    this.validateImageFile(file);

                    const compressed = await this.compressImage(file, {
                        ...options,
                        onProgress: (progress) => {
                            const overallProgress = ((i / totalFiles) * 100) + (progress / totalFiles);
                            if (options.onBatchProgress) {
                                options.onBatchProgress(overallProgress, i + 1, totalFiles);
                            }
                        }
                    });

                    results.push({
                        originalFile: file,
                        compressedDataUrl: compressed,
                        success: true
                    });

                } catch (error) {
                    results.push({
                        originalFile: file,
                        error: error.message,
                        success: false
                    });
                }
            }

            return results;
        }
    },
    watch: {
        forceClose() {
            this.resetForm();
            this.emitClose();
        },
        data(newVal) {
            if (this.data)
                this.form = this.model.updateForm(newVal);
            else
                this.form = this.model.createForm(newVal);
        },
        'form.photo': {
            handler: async function (newVal) {
                if (!newVal) return;

                try {
                    // Validate the file first
                    if (newVal instanceof File) {
                        this.validateImageFile(newVal);
                    }

                    // Use the enhanced compression with progress tracking
                    const compressedImage = await this.compressImage(newVal, {
                        maxWidth: 480, // Reduced for better performance
                        maxHeight: 270,
                        quality: 0.8,
                        maxSizeBytes: 2 * 1024 * 1024,
                        onProgress: (progress) => {
                            // You can emit progress events or update UI here
                            console.log(`Image compression progress: ${progress}%`);
                        }
                    });

                    this.form.photo = compressedImage;

                } catch (error) {
                    console.error('Image compression failed:', error);
                    // You might want to show user-friendly error message
                    this.$emit('imageCompressionError', error.message);
                }
            },
            deep: true
        }
    },
    computed: {
        User() {
            return User
        },
    },
}
