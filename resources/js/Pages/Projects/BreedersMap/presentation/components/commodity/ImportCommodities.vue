<template>
    <base-create-form :force-close="forceClose" @close="close" @submitForm="uploadForm" :form="form">
        <template #formTitle>
            Import Commodities from an Exel file
        </template>
        <template v-slot:formDescription>
            <div class="text-md text-gray-600">
                Please download the CSV template file and fill it with the commodities you want to import.
                <br />
                <p class="italic font-medium text-sm text-red-600">
                    Caution: It's important to follow the template structure to avoid errors.
                </p>
            </div>
        </template>
        <template v-slot:formFields>
            <div class="flex flex-col gap-5">
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Step 1: Download template</label>
                    <base-button @click.prevent="downloadExcelTemplate(headers, xlsxFileName, dropdowns)" class="bg-cbc-dark-green text-white">
                        <span class="p-2">Download Excel Template (.xlsx)</span>
                    </base-button>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="text-sm font-medium text-gray-700">Step 2: Fill-in the data</label>
                    <img src="/img/sample_csv_fill.png" alt="CSV Template" class="w-3/4 drop-shadow" />
                    <p>Important Notice: Please enter the breeder’s personal information first under the Breeder tab. A unique Breeder ID will be generated once the information is successfully saved. Use this ID for all related records and references.</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-700">Step 3: Upload CSV or Excel File</label>
                </div>
            </div>
            <file-field
                v-model="uploadFile"
                id="csvContent"
                label="Data File"
                type="file"
                :accept="'.csv,.xlsx,.xls,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,application/vnd.ms-excel'"
                required
                :error="errors ? errors.toString() : null"
                @change="handleFileUpload"
            />
            <div v-if="errors">
                <label v-for="error in errors" class="text-red-600">{{ error.toString() }}; </label>
            </div>
            <div class="overflow-x-scroll mt-2 flex flex-col shadow max-h-[50vh]" v-if="form && form.data && form.data.length">
                <table class="min-w-full bg-white">
                    <thead>
                    <tr class="text-center font-medium text-gray-700 bg-gray-200">
                        <th class="border p-0.5">#</th>
                        <th v-for="(value, key) in form.data[0]" :key="key" class="border p-0.5">
                            {{ key }}
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(row, index) in form.data" :key="index" class="text-center">
                        <td class="border px-1">{{ index + 1 }}</td>
                        <td v-for="(value, key) in row" :key="key" class="border px-1">
                            <input v-model="form.data[index][key]" class="p-0" />
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </template>
    </base-create-form>
</template>

<script>
import ImportMixin from "@/Pages/mixins/ImportMixin";
import Commodity from "@/Pages/Projects/BreedersMap/domain/Commodity";

export default {
    name: "ImportCommodities",
    mixins: [ImportMixin],
    beforeMount() {
        this.model = Commodity;
    }
}
</script>
