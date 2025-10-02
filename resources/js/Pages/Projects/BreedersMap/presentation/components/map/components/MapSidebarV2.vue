<script>
import CloseIcon from "@/Components/Icons/CloseIcon.vue";
import {Link} from "@inertiajs/vue3";
import TransitionContainer from "@/Components/CustomDropdown/Components/TransitionContainer.vue";

export default {
    computed: {
        instance() {
            return new this.model(this.point);
        },
        hasCustomPhoto() {
            const photo = this.instance.getProfilePhoto;
            if (!photo) return false; // null, undefined, or empty string

            // Check for base64 data URI
            if (photo.startsWith('data:image/')) return true;

            // Check if it's a known placeholder (e.g., from via.placeholder.com)
            if (photo.includes('http') || photo.includes('https')) return false;

            // Assume it's a real custom URL (S3, Facebook, etc.)
            return true;
        },
        photoStyle() {
            return {
                backgroundImage: `url('${this.instance.getProfilePhoto}')`,
            };
        },
    },
    components: {TransitionContainer, Link, CloseIcon},
    props: {
        point: Object,
        visible: Boolean,
        model: [Object, Function]
    },
    methods: {
        closeSidebar() {
            this.$emit('close');
        },
        getNestedValue(obj, path) {
            return path.split('.').reduce((acc, part) => acc && acc[part], obj);
        },
    },
}
</script>

<template>
    <transition-container>
        <div class="relative max-h-[800px] flex flex-col max-w-500px min-w-600px text-gray-800 py-2" v-show="visible">
            <div v-if="point" class="overflow-y-auto overflow-x-hidden text-sm " >
                <table class="w-full">
                    <tr v-if="hasCustomPhoto">
                        <th colspan="2" class="p-2 border-b bg-cbc-yellow-green rounded-lg">
                            <span class="block w-32 h-32 rounded-full bg-cover bg-no-repeat bg-center drop-shadow-lg mx-auto border-2" :style="photoStyle" />
                        </th>
                    </tr>

                    <tr v-else>
                        <th colspan="2"
                            class="p-2 border-b bg-cbc-yellow-green rounded-lg">
                            <!-- fallback SVG -->
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                 class="w-auto h-14 drop-shadow mx-auto" viewBox="0 0 16 16">
                                <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                                <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7 a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37 A7 7 0 0 0 8 1"/>
                            </svg>
                        </th>
                    </tr>
                    <template v-for="(value, key) in model.getCardColumns()" :key="key">
                        <tr v-if="instance[value.key] && value.visible" class="grid grid-cols-2">
                            <th class="text-right text-gray-900 whitespace-nowrap border-r p-0.5 px-2 align-text-top">{{ value.title }}</th>
                            <td class="p-0.5 px-2 hover:bg-gray-100 align-text-top">{{  getNestedValue(instance, value.key) }}</td>
                        </tr>
                    </template>
                    <tr class="bg-green-700 text-center bg-opacity-50 text-cbc-dark-green" v-if="!$page.props.auth.user">
                        <th colspan="2" class="p-3 rounded-lg">
                            <span class="font-light">To access more information about this breeder or commodity</span>
                            <Link :href="route('register')" class="block px-4 py-2 text-sm leading-5 hover:bg-cbc-yellow-green focus:outline-none focus:bg-gray-100 transition duration-300 ease-in-out rounded">
                                <span class="text-cbc-dark-green font-bold underline active:text-gray-700 ">Create your own account</span>
                            </Link>
                        </th>
                    </tr>
                </table>
            </div>
        </div>
    </transition-container>
</template>


