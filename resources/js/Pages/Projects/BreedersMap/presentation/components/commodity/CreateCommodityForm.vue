<script>
import FormMixin from "@/Pages/mixins/FormMixin";
import RequestAddCommodity from "@/Pages/Projects/BreedersMap/presentation/components/commodity/components/RequestAddCommodity.vue";
import CommodityFormMixin from "@/Pages/Projects/BreedersMap/infrastructure/CommodityFormMixin";

export default {
    name: "CreateCommodityForm",
    components: { RequestAddCommodity },
    mixins: [FormMixin, CommodityFormMixin],
};
</script>
<template>
    <base-create-form :form="form" :force-close="forceClose">
        <template v-slot:formTitle>
            Register a new Commodity
        </template>
        <template v-slot:formDescription>
            <p class="text-sm text-gray-600">
                Please fill out the form to register a new commodity.
                <br />
                Characteristics and Additional Information tabs are optional, but it is recommended to fill them out for better identification.
            </p>
        </template>
        <template v-slot:formFields>
            <tab :tabs="tabs">
                <template v-slot:tab2>
                    <label class="flex text-normal font-semibold gap-0.5 items-center whitespace-nowrap border-b py-1 mb-1">
                        Primary Identification
                    </label>
                    <div class="flex flex-col gap-8">
                        <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-2">
                            <div class="flex gap-2 items-end col-span-2">
                                <select-field required :title="getTitle('name')" :error="getError('name')" label="Commodity" v-model="form.name" @change="onCommodityChange" :options="priorityComs?.data?.data" class="w-full" />
                                <request-add-commodity />
                            </div>
                            <select-search-field :title="getTitle('breeder_id')" required :api-link="route('api.breeders.selections')" :disabled="isInitialzedBreeeder"  :error="getError('breeder_id')" label="Breeder/Organization" v-model="form.breeder_id" />
                            <text-field required :title="getTitle('accession')" :error="getError('accession')" label="Variety/Accession No./Germplasm Index" v-model="form.accession" />
                            <text-field required :title="getTitle('yield')" type-input="number" :error="getError('yield')" label="Yield" v-model="form.yield" />
                        </div>
                        <select-search-field :title="getTitle('geolocation')" required :api-link="route('api.cities.options.public')"  :error="getError('geolocation')" label="Location" v-model="form.geolocation" />
                        <text-field type-input="longtext" :title="getTitle('description')" :error="getError('description')" label="Other Unique Traits" v-model="form.description" />
                        <file-field :error="getError('photo')" :title="getTitle('photo')" accept="image/png, image/jpeg, image/jpg, image/heic" label="Profile Photo" v-model="form.photo"  />
                    </div>
                </template>
                <template v-slot:tab1>
                    <div class="flex flex-col gap-8">
                        <div class="flex flex-col text-gray-600 gap-5">
                            <div>
                                <label class="flex text-lg font-semibold gap-0.5 items-center whitespace-nowrap">
                                    Stress Resilience
                                </label>
                                <p class="flex gap-0.5 items-center whitespace-nowrap border-b py-1 mb-1">
                                    Biotic stress resistance and Abiotic stress tolerance.
                                </p>
                                <div class="flex flex-row gap-2 w-full my-2 bg-cbc-yellow">
                                    <div class="flex flex-row gap-2 w-full items-center">
                                        <p v-for="item in [
                                            'Type',
                                            'Stress Condition',
                                            'Reaction',
                                        ]" v-bind:key="item" class="leading-none w-full font-bold text-center text-normal gap-0.5 items-center whitespace-nowrap">
                                            {{ item }}
                                        </p>
                                    </div>
                                    <base-button classes="opacity-0 h-fit w-fit p-2 bg-cbc-yellow-green text-gray-900 hover:bg-cbc-dark-green hover:text-white">
                                        <add-icon class="w-auto h-6" />
                                    </base-button>
                                </div>
                                <div class="flex flex-col gap-2">
                                    <div
                                        v-for="(stress_resilience, index) in form.stress_resilience"
                                        :key="index"
                                        class="flex flex-col items-end gap-2"
                                    >
                                        <div class="flex gap-2 w-full items-center">
                                            <div class="grid grid-cols-3 gap-1 w-full">
                                                <select-field :options="Object.keys(stress_resilience_options).map((item)=>{ return { label: item, value: item } })" :error="getError(`stress_resilience[${index}].type`)" v-model="stress_resilience.type" />
                                                <div class="flex gap-1 w-full">
                                                    <select-field :options="form?.stress_resilience[index]?.type ? Object.values(stress_resilience_options[form?.stress_resilience[index]?.type]?.conditions).map((item)=>{ return { label: item, value: item } }) : [{ label: 'Select a type first', value: null }]" :error="getError(`stress_resilience[${index}].stress`)" v-model="stress_resilience.stress" class="w-full" />
                                                    <text-field v-if="form?.stress_resilience[index]?.type === 'Biotic'" v-model="stress_resilience.stress" />
                                                </div>
                                                <select-field :options="form?.stress_resilience[index]?.type ? Object.values(stress_resilience_options[form?.stress_resilience[index]?.type]?.reactions).map((item)=>{ return { label: item, value: item } }) : [{ label: 'Select a stress condition', value: null }]" :error="getError(`stress_resilience[${index}].reaction`)" v-model="stress_resilience.reaction" />
                                            </div>

                                            <!-- Remove Button (X) -->
                                            <base-button
                                                v-if="form.stress_resilience.length > 1"
                                                @click.prevent="removeStressResilience(index)"
                                                classes="h-fit w-fit p-2 bg-red-100 text-red-600 hover:bg-red-600 hover:text-white"
                                            >
                                                <close-icon class="w-auto h-6" />
                                            </base-button>
                                        </div>

                                        <!-- Add Button (+) -->
                                        <base-button
                                            v-if="index === form.stress_resilience.length - 1"
                                            @click.prevent="addStressResilience"
                                            classes="h-fit w-fit p-2 bg-cbc-yellow-green text-gray-900 hover:bg-cbc-dark-green hover:text-white"
                                        >
                                            <add-icon class="w-auto h-6" />
                                        </base-button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-col text-gray-600 gap-5">
                            <div>
                                <label class="flex text-normal font-semibold gap-0.5 items-center whitespace-nowrap border-b py-1 mb-1">
                                    Plant Characteristics
                                </label>
                                <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-2">
                                    <text-field
                                        type-input="number"
                                        :error="getError('weight')"
                                        label="Weight (grams)"
                                        :title="getTitle('weight')"
                                        v-model="form.weight"
                                    />
                                    <text-field
                                        type-input="number"
                                        :error="getError('length')"
                                        label="Length (cm)"
                                        :title="getTitle('length')"
                                        v-model="form.length"
                                    />
                                    <text-field
                                        type-input="number"
                                        :error="getError('width')"
                                        label="Width (cm)"
                                        :title="getTitle('width')"
                                        v-model="form.width"
                                    />
                                    <text-field
                                        :error="getError('shape')"
                                        label="Shape"
                                        :title="getTitle('shape')"
                                        v-model="form.shape"
                                    />
                                </div>
                            </div>

                            <!-- Fruit Characteristics -->
                            <div>
                                <label class="flex text-normal font-semibold gap-0.5 items-center whitespace-nowrap border-b py-1 mb-1">
                                    Fruit Characteristics
                                </label>
                                <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-2">
                                    <text-field
                                        :error="getError('skin_color')"
                                        label="Skin Color"
                                        :title="getTitle('skin_color')"
                                        v-model="form.skin_color"
                                    />
                                    <text-field
                                        :error="getError('skin_texture')"
                                        :title="getTitle('skin_texture')"
                                        label="Skin Texture"
                                        v-model="form.skin_texture"
                                    />
                                    <text-field
                                        :error="getError('flesh_color')"
                                        label="Flesh Color"
                                        :title="getTitle('flesh_color')"
                                        v-model="form.flesh_color"
                                    />
                                    <text-field
                                        :error="getError('flesh_texture')"
                                        :title="getTitle('flesh_texture')"
                                        label="Flesh Texture"
                                        v-model="form.flesh_texture"
                                    />
                                    <text-field
                                        :error="getError('flesh_flavor')"
                                        :title="getTitle('flesh_flavor')"
                                        label="Flesh Flavor"
                                        v-model="form.flesh_flavor"
                                    />
                                    <text-field
                                        :error="getError('aroma')"
                                        :title="getTitle('aroma')"
                                        label="Aroma"
                                        v-model="form.aroma"
                                    />
                                </div>
                            </div>

                            <!-- Root Characteristics -->
                            <div>
                                <label class="flex text-normal font-semibold gap-0.5 items-center whitespace-nowrap border-b py-1 mb-1">
                                    Root Characteristics
                                </label>
                                <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-2">
                                    <text-field
                                        :error="getError('root_flesh_color')"
                                        :title="getTitle('root_flesh_color')"
                                        label="Root Flesh Color"
                                        v-model="form.root_flesh_color"
                                    />
                                    <text-field
                                        :error="getError('root_cortex_color')"
                                        :title="getTitle('root_cortex_color')"
                                        label="Root Cortex Color"
                                        v-model="form.root_cortex_color"
                                    />
                                    <text-field
                                        :error="getError('root_skin_color')"
                                        :title="getTitle('root_skin_color')"
                                        label="Root Skin Color"
                                        v-model="form.root_skin_color"
                                    />
                                    <text-field
                                        :error="getError('root_shape')"
                                        :title="getTitle('root_shape')"
                                        label="Root Shape"
                                        v-model="form.root_shape"
                                    />
                                </div>
                            </div>

                            <!-- Tuber Characteristics -->
                            <div>
                                <label class="flex text-normal font-semibold gap-0.5 items-center whitespace-nowrap border-b py-1 mb-1">
                                    Tuber Characteristics
                                </label>
                                <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-2">
                                    <text-field
                                        :error="getError('tuber_flesh_color')"
                                        :title="getTitle('tuber_flesh_color')"
                                        label="Tuber Flesh Color"
                                        v-model="form.tuber_flesh_color"
                                    />
                                    <text-field
                                        :error="getError('tuber_cortex_color')"
                                        :title="getTitle('tuber_cortex_color')"
                                        label="Tuber Cortex Color"
                                        v-model="form.tuber_cortex_color"
                                    />
                                    <text-field
                                        :error="getError('tuber_skin_color')"
                                        :title="getTitle('tuber_skin_color')"
                                        label="Tuber Skin Color"
                                        v-model="form.tuber_skin_color"
                                    />
                                    <text-field
                                        :error="getError('tuber_shape')"
                                        :title="getTitle('tuber_shape')"
                                        label="Tuber Shape"
                                        v-model="form.tuber_shape"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
                <template v-slot:tab3>
                    <div class="flex flex-col gap-8">
                        <div class="flex flex-col text-gray-600 gap-5">
                            <div>
                                <label class="flex text-lg font-semibold gap-0.5 items-center whitespace-nowrap">
                                    Legal and Regulatory Compliance
                                </label>
                                <p class="flex gap-0.5 items-center whitespace-nowrap border-b py-1 mb-1">
                                    List any legal or regulatory compliance information related to the commodity, such as certifications, registrations, or approvals.
                                </p>
                                <div>
                                    <h3 class="font-bold">
                                        List of Regulatory Bodies
                                    </h3>
                                    <ul class="grid grid-cols-2 leading-none" style="list-style: disc; list-style-position: inside">
                                        <li>
                                            NSIC Registration
                                        </li>
                                        <li>
                                            Plant Variety Protection
                                        </li>
                                        <li>
                                            NCBP
                                        </li>
                                        <li>
                                            AO8
                                        </li>
                                        <li>
                                            JDC 2016
                                        </li>
                                        <li>
                                            JDC 2021
                                        </li>
                                    </ul>
                                </div>
                                <div class="flex flex-row gap-2 w-full bg-cbc-yellow my-2">
                                    <div class="flex flex-row gap-2 w-full items-center">
                                        <p v-for="item in [
                                            'Regulatory Body',
                                            'Cert./Reg. No.',
                                            'Date Issued',
                                        ]" v-bind:key="item" class="leading-none w-full font-bold text-center text-normal gap-0.5 items-center whitespace-nowrap">
                                            {{ item }}
                                        </p>
                                    </div>
                                    <base-button classes="opacity-0 h-fit w-fit p-2 bg-cbc-yellow-green text-gray-900 hover:bg-cbc-dark-green hover:text-white">
                                        <add-icon class="w-auto h-6" />
                                    </base-button>
                                </div>
                                <div class="flex flex-col gap-4">
                                    <div
                                        v-for="(regulation, index) in form.regulations"
                                        :key="index"
                                        class="flex flex-col items-end gap-2"
                                    >
                                        <div class="flex items-center gap-2 w-full" >
                                            <div class="grid grid-cols-3 gap-2 w-full">
                                                <text-field
                                                    :error="getError(`regulations[${index}].regulatory_body`)"
                                                    v-model="regulation.regulatory_body"
                                                />
                                                <text-field
                                                    :error="getError(`regulations[${index}].registration_no`)"
                                                    v-model="regulation.registration_no"
                                                />
                                                <date-field :error="getError(`regulations[${index}].registration_date`)"
                                                            v-model="regulation.registration_date"/>
                                            </div>

                                            <!-- Remove Button (X) -->
                                            <base-button
                                                v-if="form.regulations.length > 1"
                                                @click.prevent="removeRegulation(index)"
                                                classes="h-fit w-fit p-2 bg-red-100 text-red-600 hover:bg-red-600 hover:text-white"
                                            >
                                                <close-icon class="w-auto h-6" />
                                            </base-button>
                                        </div>

                                        <!-- Add Button (+) -->
                                        <base-button
                                            v-if="index === form.regulations.length - 1"
                                            @click.prevent="addRegulation"
                                            classes="h-fit w-fit p-2 bg-cbc-yellow-green text-gray-900 hover:bg-cbc-dark-green hover:text-white"
                                        >
                                            <add-icon class="w-auto h-6" />
                                        </base-button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </template>
            </tab>
        </template>
    </base-create-form>
</template>
