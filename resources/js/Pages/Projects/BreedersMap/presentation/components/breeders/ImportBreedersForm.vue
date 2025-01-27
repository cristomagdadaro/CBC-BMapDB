<template>
    <base-create-form :force-close="forceClose" @close="close" @submitForm="uploadForm" :form="form">
        <template #formTitle>
            Import Breeders from a CSV file
        </template>
        <template v-slot:formDescription>
            <div class="text-md text-gray-600">
                Please download the CSV template file and fill it with the breeders you want to import.
                <br />
                <p class="italic font-medium text-sm text-red-600">
                    Caution: It's important to follow the template structure to avoid errors.
                    Caution: It's important to follow the template structure to avoid errors.
                </p>
            </div>
        </template>
        <template v-slot:formFields>
            <div class="flex flex-col gap-5">
                <div>
                    <label class="text-sm font-medium text-gray-700">Step 1: Download template</label>
                    <base-button @click.prevent="downloadCsvTemplate(headers, fileName)" class="bg-cbc-dark-green text-white">
                        <span class="p-2">Download CSV Template</span>
                    </base-button>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Step 2: Fill-in the data</label>
                    <img src="/img/sample_csv_fill.png" alt="CSV Template" class="w-3/4 drop-shadow" />
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Step 3: Upload CSV File</label>

                </div>
            </div>

            <file-field
                v-model="form.csvContent"
                id="csvContent"
                label="CSV File"
                type="file"
                required
                :error="errors ? errors.csvContent : null"
                @change="handleFileUpload"
            />
            <div v-if="form.csvContent">
                <label v-for="error in form.csvContent.errors" class="text-red-600">{{ error.message }}</label>
            </div>
            <div class="overflow-x-scroll mt-2 flex flex-col shadow max-h-[50vh]" v-if="form.csvContent && form.csvContent.data && form.csvContent.data.length">
                <table class="min-w-full bg-white">
                    <thead>
                    <tr class="text-center font-medium text-gray-700 bg-gray-200">
                        <th class="border p-0.5">#</th>
                        <th v-for="(value, key) in form.csvContent.data[0]" :key="key" class="border p-0.5">
                            {{ key }}
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(row, index) in form.csvContent.data" :key="index" class="text-center">
                        <td class="border px-1">{{ index + 1 }}</td>
                        <td v-for="(value, key) in row" :key="key" class="border px-1">
                            {{ value }}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </template>
    </base-create-form>
</template>

<script>
import BaseCreateForm from "@/Components/Modal/BaseCreateForm.vue";
import SelectSearchField from "@/Components/Form/SelectSearchField.vue";
import RadioField from "@/Components/Form/RadioField.vue";
import BaseButton from "@/Components/CRCMDatatable/Components/BaseButton.vue";
import FileField from "@/Components/Form/FileField.vue";
import Breeder from "@/Pages/Projects/BreedersMap/domain/Breeder";
import ImportMixin from "@/Pages/mixins/ImportMixin.js";

export default {
    name: "ImportBreedersForm",
    mixins: [ImportMixin],
    components: { FileField, BaseButton, RadioField, SelectSearchField, BaseCreateForm },
    computed: {
        headers() {
            return Object.keys(Breeder.createForm());
        },
        fileName() {
            return 'import_breeders_template.csv';
        }
    },
}
</script>

<style scoped>

</style>
