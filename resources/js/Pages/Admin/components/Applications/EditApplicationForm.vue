<script>
import FormMixin from "@/Pages/mixins/FormMixin.js";

export default {
    mixins: [FormMixin],
    name: "EditApplicationForm",
    data() {
        return {
            form: {
                name: null,
                description: null,
                url: null,
                icon: null,
                status: null,
            },
        };
    },
}
</script>

<template>
    <base-edit-form :form="form" :forceClose="forceClose" @resetForm="resetForm">
        <template v-slot:formTitle>
            Update Details for Database Application
        </template>
        <template v-slot:formFields>
            <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                <text-field required :error="getError('name')" label="Name" v-model="form.name" />
                <text-field required :error="getError('description')" label="Description" v-model="form.description" />
                <text-field required :error="getError('url')" label="Route Name" v-model="form.url" />
                <text-field class="hidden" :error="getError('icon')" label="Icon" v-model="form.icon" />
                <radio-field required :error="getError('status')" label="Status" v-model="form.status" :options="[{label: 'Active', value: 'true'}, {label: 'Inactive', value: 'false'}]" />
            </div>
        </template>
    </base-edit-form>
</template>
