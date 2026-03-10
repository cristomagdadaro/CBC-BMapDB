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
            if (!photo) return false;
            if (typeof photo === 'string' && photo.startsWith('data:image/')) return true;
            if (typeof photo === 'string' && (photo.includes('http') || photo.includes('https'))) return false;
            return true;
        },
        photoStyle() {
            return { backgroundImage: `url('${this.instance.getProfilePhoto}')` };
        },
        // Role detection
        roleNames() {
            const roles = this.$page?.props?.auth?.user?.roles || [];
            return roles.map(role => role?.name ?? role).filter(Boolean);
        },
        isAdmin() {
            return this.roleNames.includes('Administrator');
        },
        isFocal() {
            return this.roleNames.includes('Focal Person');
        },
        isBreederRole() {
            return this.roleNames.includes('Breeder');
        },
        currentUserId() {
            return this.$page?.props?.auth?.user?.id || null;
        },
        currentUserAffiliationId() {
            return this.$page?.props?.auth?.user?.affiliated?.id || null;
        },
        // Ownership and institute checks
        isCommodity() {
            return String(this.modelName || '').toLowerCase() === 'commodity';
        },
        isBreeder() {
            return String(this.modelName || '').toLowerCase() === 'breeder';
        },
        ownsCommodity() {
            if (!this.isCommodity) return false;
            const row = this.point || {};
            const uid = Number(this.currentUserId);
            const direct = Number(row.user_id) === uid;
            const breederUid = Number(row?.breeder?.user_id || this.instance?.breeder?.user_id) === uid;
            return !!uid && (direct || breederUid);
        },
        sameInstituteCommodity() {
            if (!this.isCommodity) return false;
            const userAff = Number(this.currentUserAffiliationId);
            const rowAff = Number(this.point?.breeder?.affiliation || this.instance?.breeder?.affiliation);
            return !!userAff && !!rowAff && userAff === rowAff;
        },
        sameInstituteBreeder() {
            if (!this.isBreeder) return false;
            const userAff = Number(this.currentUserAffiliationId);
            const rowAff = Number(this.point?.affiliation || this.instance?.affiliated?.id);
            return !!userAff && !!rowAff && userAff === rowAff;
        },
        // Final permissions
        canUpdate() {
            if (!this.$page?.props?.auth?.user) return false;
            if (this.isAdmin) return true;
            if (this.isCommodity) return (this.isFocal && this.sameInstituteCommodity) || (this.isBreederRole && this.ownsCommodity);
            if (this.isBreeder) return (this.isFocal && this.sameInstituteBreeder);
            return false;
        },
        canDelete() {
            if (!this.$page?.props?.auth?.user) return false;
            if (this.isAdmin) return true;
            if (this.isCommodity) return this.isFocal && this.sameInstituteCommodity;
            if (this.isBreeder) return this.isFocal && this.sameInstituteBreeder;
            return false;
        },
        viewRouteName() {
            if (this.isCommodity) return 'breedersmap.commodity.view';
            if (this.isBreeder) return 'breedersmap.breeder.view';
            return null;
        }
    },
    components: {TransitionContainer, Link, CloseIcon},
    props: {
        point: Object,
        visible: Boolean,
        model: [Object, Function],
        modelName: {
            type: String,
            default: ''
        }
    },
    methods: {
        closeSidebar() {
            this.$emit('close');
        },
        getNestedValue(obj, path) {
            return path.split('.').reduce((acc, part) => acc && acc[part], obj);
        },
        onEdit() {
            this.$emit('edit', { point: this.point, modelName: this.modelName });
        },
        onDelete() {
            this.$emit('delete', { point: this.point, modelName: this.modelName });
        }
    },
}
</script>

<template>
    <transition-container>
        <div class="relative max-h[800px] flex flex-col max-w-500px min-w-600px text-gray-800 py-2" v-show="visible">
            <div v-if="point" class="overflow-y-auto overflow-x-hidden text-sm ">
                <table class="w-full">
                    <tr v-if="hasCustomPhoto">
                        <th colspan="2" class="p-2 border-b bg-cbc-yellow-green rounded-lg">
                            <span class="block w-32 h-32 rounded-full bg-cover bg-no-repeat bg-center drop-shadow-lg mx-auto border-2" :style="photoStyle" />
                        </th>
                    </tr>
                    <tr v-else>
                        <th colspan="2" class="p-2 border-b bg-cbc-yellow-green rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="w-auto h-14 drop-shadow mx-auto" viewBox="0 0 16 16">
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

                    <tr v-else>
                        <th colspan="2" class="p-2 border-t">
                            <div class="flex gap-2 justify-center">
                                <Link v-if="viewRouteName" class="bg-view rounded px-2 py-1 text-white" :href="route(viewRouteName, instance.id)">View</Link>
                                <button v-if="canUpdate" class="bg-edit rounded px-2 py-1 text-white" @click.prevent="onEdit">Edit</button>
                                <button v-if="canDelete" class="bg-delete rounded px-2 py-1 text-white" @click.prevent="onDelete">Delete</button>
                            </div>
                        </th>
                    </tr>
                </table>
            </div>
        </div>
    </transition-container>
</template>
