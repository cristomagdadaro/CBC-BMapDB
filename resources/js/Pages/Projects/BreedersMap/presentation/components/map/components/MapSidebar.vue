<script>
import CloseIcon from "@/Components/Icons/CloseIcon.vue";
import {Link} from "@inertiajs/vue3";
import TransitionContainer from "@/Components/CustomDropdown/Components/TransitionContainer.vue";

export default {
    computed: {
        instance() {
            return new this.model(this.point);
        }
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
    }
}
</script>

<template>
    <transition-container type="slide-right">
        <div class="relative max-h-[800px] flex flex-col max-w-1/2 bg-gray-100 rounded" v-show="visible">
            <button @click="closeSidebar" class="absolute z-[999] top-0 w-fit right-3 p-1">
                <close-icon class="w-8 h-8 drop-shadow text-white" />
            </button>
            <div v-if="point" class="drop-shadow overflow-y-auto overflow-x-hidden border text-sm">
                <table>
                    <tr class="bg-cbc-dark-green text-white text-center uppercase">
                        <th colspan="2" class="p-2">Commodity Details</th>
                    </tr>
                    <template v-for="(value, key) in model.getCardColumns()" :key="key">
                        <tr v-if="instance[value.key] && value.visible" >
                            <th class="text-right text-gray-900 whitespace-nowrap bg-gray-300 p-0.5 px-2">{{ value.title }}</th>
                            <td class="p-0.5 px-2">{{  getNestedValue(instance, value.key) }}</td>
                        </tr>
                    </template>
                    <tr class="bg-cbc-dark-green text-white text-center font-light" v-if="!$page.props.auth.user">
                        <th colspan="2" class="p-2 font-light">
                            To access more information about this commodity
                            <Link :href="route('register')" class="block px-4 py-2 text-sm leading-5 hover:bg-cbc-yellow-green focus:outline-none focus:bg-gray-100 transition duration-300 ease-in-out rounded">
                                <span class="text-white underline active:text-gray-700 ">Create your own account</span>
                            </Link>
                        </th>
                    </tr>
                </table>
            </div>
        </div>
    </transition-container>
</template>


