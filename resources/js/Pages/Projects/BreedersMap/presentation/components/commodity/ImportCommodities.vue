<template>
    <div class="p-5">
        <h1 class="text-lg">Import Commodities from a CSV file</h1>
        <div class="text-md text-gray-600">
            Please download the CSV template file and fill it with the commodities you want to import.
            <br />
            <p class="italic font-medium text-sm text-red-600">
                Caution: It's important to follow the template structure to avoid errors.
            </p>
        </div>
        <div class="flex flex-col gap-5">
            <div>
                <label class="text-sm font-medium text-gray-700">Step 1: Download template</label>
                <base-button @click="downloadCsvTemplate" class="bg-cbc-dark-green text-white">
                    <span class="p-2">Download CSV Template</span>
                </base-button>
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700">Step 2: Fill-in the data</label>
                <img src="/img/sample_csv_fill.png" alt="CSV Template" class="w-3/4 drop-shadow" />
            </div>
            <div>
                <label class="text-sm font-medium text-gray-700">Step 3: Upload CSV File</label>
                <base-create-form :force-close="forceClose" @close="close" @uploadForm="uploadForm" :form="form" class="bg-cbc-dark-green text-white">
                    <template v-slot:formFields>
                        <file-field
                            v-model="form.csvContent"
                            id="csvContent"
                            label="CSV File"
                            type="file"
                            required
                            :error="errors.csvContent"
                            @change="handleFileUpload"
                        />

                        <div class="overflow-x-scroll mt-2 flex flex-col shadow max-h-[50vh]" v-if="form.csvContent && form.csvContent.length">
                            <table class="min-w-full bg-white">
                                <thead>
                                <tr class="text-center font-medium text-gray-700 bg-gray-200">
                                    <th class="border p-0.5">#</th>
                                    <th v-for="(value, key) in form.csvContent[0]" :key="key" class="border p-0.5">
                                        {{ key }}
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="(row, index) in form.csvContent" :key="index" class="text-center">
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
            </div>
        </div>
    </div>
</template>

<script>
import BaseCreateForm from "@/Components/Modal/BaseCreateForm.vue";
import SelectSearchField from "@/Components/Form/SelectSearchField.vue";
import RadioField from "@/Components/Form/RadioField.vue";
import BaseButton from "@/Components/CRCMDatatable/Components/BaseButton.vue";
import FileField from "@/Components/Form/FileField.vue";
import Papa from "papaparse";

export default {
    name: "ImportCommodities",
    components: { FileField, BaseButton, RadioField, SelectSearchField, BaseCreateForm },
    props: {
        errors: {
            type: Object,
            default: () => ({})
        },
        forceClose: {
            type: Boolean,
            default: false
        },
        data: {
            type: Object,
            default: null
        }
    },
    data() {
        return {
            form: {
                csvContent: null,
            }
        };
    },
    methods: {
        close() {
            this.$emit('close');
        },
        uploadForm() {
            this.$emit('uploadForm', this.form.csvContent);
        },
        resetForm() {
            this.form = Object.assign({}, this.data);
            this.$emit('close');
        },
        downloadCsvTemplate() {
            const headers = [
                'name', 'breeder_id', 'scientific_name', 'variety', 'accession',
                'germplasm', 'population', 'maturity_period', 'yield', 'description',
                'image', 'latitude', 'longitude', 'address', 'city', 'province', 'country',
                'postal_code', 'formatted_address', 'place_id', 'status'
            ];

            // Join headers with comma for CSV header row
            const csvHeader = headers.join(',');

            // Create CSV content with header row
            let csvContent = csvHeader + '\r\n';

            // Create a Blob object for the CSV content
            const blob = new Blob([csvContent], {type: 'text/csv;charset=utf-8;'});

            // Create a temporary link element
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);

            // Set link attributes for downloading
            link.setAttribute('href', url);
            link.setAttribute('download', 'template.csv');

            // Append link to the body and click it programmatically
            document.body.appendChild(link);
            link.click();

            // Clean up
            document.body.removeChild(link);
            URL.revokeObjectURL(url);
        },
        handleFileUpload(event) {
            const file = event.target.files[0];
            this.importCSV(file);
        },
        importCSV(file) {
            const reader = new FileReader();
            reader.onload = (event) => {
                const csvData = event.target.result;
                Papa.parse(csvData, {
                    header: true,
                    complete: (results) => {
                        this.form.csvContent = results.data; // Update the form data with the parsed CSV content
                    },
                    error: (error) => {
                        console.error('Error parsing CSV:', error);
                    },
                });
            };
            reader.readAsText(file);
        }
    },
    watch: {
        forceClose() {
            this.close();
        },
        data() {
            this.form = Object.assign({}, this.data);
        }
    },
}
</script>

<style scoped>

</style>
