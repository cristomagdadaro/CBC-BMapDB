<script>
import InputError from "@/Components/InputError.vue";

export default {
    name: "FileField",
    components: { InputError },
    emits: ["update:modelValue"], // Declare emitted events explicitly
    props: {
        accept: String,
        modelValue: [Object, String],
        label: String,
        error: {
            type: [String, Array],
            default: () => [],
        },
        required: {
            type: Boolean,
            default: false,
        },
        showClear: {
            type: Boolean,
            default: false,
        },
        id: String,
        title: {
            type: String,
            default: null,
        }
    },
    data() {
        return {
            fileName: null,
            previewUrl: null,
            validationError: null,
        };
    },
    watch: {
        modelValue(newFile) {
            if (!newFile) {
                this.fileName = null;
                this.previewUrl = null;
                this.validationError = null;
            }
        }
    },
    methods: {
        handleFileChange(event) {
            const file = event.target.files[0];
            if (!file) return;

            // Validate file type
            const allowedTypes = ["image/png", "image/jpeg", "image/jpg", "image/heic"];
            if (!allowedTypes.includes(file.type)) {
                this.validationError = "Invalid file type. Only JPG, JPEG, PNG, and HEIC are allowed.";
                this.clearFile();
                return;
            }

            // Validate file size (5MB limit)
            if (file.size > 5120 * 1024) {
                this.validationError = "File size exceeds 5MB.";
                this.clearFile();
                return;
            }

            // Set file name and preview
            this.fileName = file.name;
            this.previewUrl = URL.createObjectURL(file);
            this.validationError = null;

            // Emit the file update
            this.$emit("update:modelValue", file);
        },
        clearFile() {
            this.fileName = null;
            this.previewUrl = null;
            this.validationError = null;
            this.$emit("update:modelValue", null);
        },
    }
};
</script>

<template>
    <div class="flex flex-col border-0 p-0 bg-transparent">
        <div class="flex justify-between items-center">
            <label :for="id" class="flex gap-0.5 items-center whitespace-nowrap justify-between px-1">
                {{ label }}
                <span v-if="required" class="text-red-500 font-bold text-normal">*</span>
            </label>
            <InputError v-if="error && error?.length" :message="Array.isArray(error) ? error[0] : error" />
            <InputError v-else-if="validationError" :message="validationError" />
            <span v-else class="text-gray-600 opacity-50 font-bold text-xs">
                    {{ title }}
            </span>
        </div>

       <div class="flex gap-2">
           <div class="flex relative rounded-md shadow-sm border bg-white hover:ring-1 active:ring-1 items-center w-full"
                :class="error && error.length ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-gray-300 focus:border-indigo-500 overflow-ellipsis focus:ring-indigo-500'">
               <input :id="id"
                      type="file"
                      :accept="accept"
                      @change="handleFileChange"
                      class="border-0 w-full rounded-md focus:ring-0 overflow-ellipsis p-2"
               >
           </div>

           <!-- Image Preview -->
           <span
               v-if="previewUrl || modelValue"
               class="block w-20 h-20 bg-cover bg-no-repeat bg-center drop-shadow border"
               :style="'background-image: url(\'' + modelValue ?? previewUrl + '\');'"
           />
       </div>
        <!-- Clear Button -->
        <button v-if="showClear && (fileName || modelValue)"
                type="button"
                class="mt-2 px-4 py-1 bg-red-500 text-white text-xs rounded hover:bg-red-600"
                @click.prevent="clearFile">
            Remove
        </button>
    </div>
</template>
