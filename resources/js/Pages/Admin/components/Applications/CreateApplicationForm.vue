<script>
import FormMixin from "@/Pages/mixins/FormMixin.js";

export default {
    mixins: [FormMixin],
    name: "CreateApplicationForm",
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
    <base-create-form :form="form" :forceClose="forceClose">
        <template v-slot:formTitle>
            Create new Database Application
        </template>
        <template v-slot:formFields>
            <div class="grid sm:grid-cols-2 grid-cols-1 text-sm text-gray-600 gap-1">
                <text-field required :error="getError('name')" label="Name" v-model="form.name" />
                <text-field required :error="getError('description')" label="Description" v-model="form.description" />
                <text-field :error="getError('url')" label="Route Name" v-model="form.url" />
                <text-field class="hidden" :error="getError('icon')" label="Icon" v-model="form.icon" />
                <radio-field required :error="getError('status')" label="Status" v-model="form.status" :options="[{label: 'Active', value: true}, {label: 'Inactive', value: false}]" />
            </div>
        </template>
    </base-create-form>
</template>
