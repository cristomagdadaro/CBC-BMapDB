<template>
    <div class="export-example-component">
        <div class="card">
            <div class="card-header">
                <h3>Data Export Example</h3>
            </div>
            <div class="card-body">
                <!-- Filters -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Filter by Region</label>
                        <select v-model="selectedRegion" class="form-select">
                            <option value="">All Regions</option>
                            <option value="Luzon">Luzon</option>
                            <option value="Visayas">Visayas</option>
                            <option value="Mindanao">Mindanao</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Filter by Status</label>
                        <select v-model="selectedStatus" class="form-select">
                            <option value="">All Status</option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">&nbsp;</label>
                        <button @click="resetFilters" class="btn btn-secondary w-100">
                            Reset Filters
                        </button>
                    </div>
                </div>

                <!-- Export Buttons -->
                <div class="export-actions mb-4">
                    <button
                        @click="handleExportExcel"
                        class="btn btn-success me-2"
                        :disabled="isExporting"
                    >
                        <i class="bi bi-file-excel"></i> Export to Excel
                    </button>
                    <button
                        @click="handleExportPDF"
                        class="btn btn-danger me-2"
                        :disabled="isExporting"
                    >
                        <i class="bi bi-file-pdf"></i> Export to PDF
                    </button>
                    <button
                        @click="handleExportBoth"
                        class="btn btn-primary"
                        :disabled="isExporting"
                    >
                        <i class="bi bi-download"></i> Export Both
                    </button>
                </div>

                <!-- Data Summary -->
                <div class="alert alert-info">
                    <strong>Total Records:</strong> {{ filteredData.length }} / {{ sampleData.length }}
                </div>

                <!-- Data Table -->
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Region</th>
                                <th>Province</th>
                                <th>Municipality</th>
                                <th>Commodity</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="item in filteredData" :key="item.id">
                                <td>{{ item.id }}</td>
                                <td>{{ item.name }}</td>
                                <td>{{ item.region }}</td>
                                <td>{{ item.province }}</td>
                                <td>{{ item.municipality }}</td>
                                <td>{{ item.commodity }}</td>
                                <td>
                                    <span
                                        :class="{
                                            'badge bg-success': item.status === 'Active',
                                            'badge bg-secondary': item.status === 'Inactive'
                                        }"
                                    >
                                        {{ item.status }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        <div v-if="message" class="alert mt-3" :class="messageClass" role="alert">
            {{ message }}
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { useExport } from '@/composables/useExport';
import type { ExportColumn } from '@/utils/ExportService';

// Composables
const { exportToExcel, exportToPDF, exportToAll } = useExport();

// State
const isExporting = ref(false);
const message = ref('');
const messageClass = ref('alert-success');
const selectedRegion = ref('');
const selectedStatus = ref('');

// Sample data (replace with your actual data source)
const sampleData = ref([
    { id: 1, name: 'Juan dela Cruz', region: 'Luzon', province: 'Laguna', municipality: 'Los Baños', commodity: 'Rice', status: 'Active' },
    { id: 2, name: 'Maria Santos', region: 'Visayas', province: 'Cebu', municipality: 'Cebu City', commodity: 'Corn', status: 'Active' },
    { id: 3, name: 'Pedro Reyes', region: 'Mindanao', province: 'Davao del Sur', municipality: 'Davao City', commodity: 'Banana', status: 'Inactive' },
    { id: 4, name: 'Ana Garcia', region: 'Luzon', province: 'Pampanga', municipality: 'Angeles City', commodity: 'Vegetables', status: 'Active' },
    { id: 5, name: 'Jose Rizal', region: 'Luzon', province: 'Rizal', municipality: 'Antipolo', commodity: 'Coffee', status: 'Active' },
]);

// Computed filtered data
const filteredData = computed(() => {
    return sampleData.value.filter(item => {
        const matchRegion = !selectedRegion.value || item.region === selectedRegion.value;
        const matchStatus = !selectedStatus.value || item.status === selectedStatus.value;
        return matchRegion && matchStatus;
    });
});

// Export column configuration
const exportColumns: ExportColumn[] = [
    { header: 'ID', key: 'id', width: 8 },
    { header: 'Breeder Name', key: 'name', width: 25 },
    { header: 'Region', key: 'region', width: 15 },
    { header: 'Province', key: 'province', width: 20 },
    { header: 'Municipality', key: 'municipality', width: 20 },
    { header: 'Commodity', key: 'commodity', width: 15 },
    { header: 'Status', key: 'status', width: 12 }
];

// Export handlers
const handleExportExcel = async () => {
    if (filteredData.value.length === 0) {
        showMessage('No data to export!', 'alert-warning');
        return;
    }

    try {
        isExporting.value = true;
        await exportToExcel({
            filename: `data-export-${Date.now()}`,
            sheetName: 'Data Export',
            title: 'Sample Data Export',
            columns: exportColumns,
            data: filteredData.value,
            metadata: {
                'Total Records': filteredData.value.length,
                'Region Filter': selectedRegion.value || 'All',
                'Status Filter': selectedStatus.value || 'All',
                'Export Date': new Date().toLocaleString(),
                'Generated By': 'CBC BioMap Database System'
            }
        });
        showMessage('Excel file exported successfully!', 'alert-success');
    } catch (error) {
        console.error('Excel export error:', error);
        showMessage('Failed to export Excel file. Please try again.', 'alert-danger');
    } finally {
        isExporting.value = false;
    }
};

const handleExportPDF = () => {
    if (filteredData.value.length === 0) {
        showMessage('No data to export!', 'alert-warning');
        return;
    }

    try {
        isExporting.value = true;
        exportToPDF({
            filename: `data-export-${Date.now()}`,
            title: 'Sample Data Export',
            columns: exportColumns,
            data: filteredData.value,
            metadata: {
                'Total Records': filteredData.value.length,
                'Region Filter': selectedRegion.value || 'All',
                'Status Filter': selectedStatus.value || 'All',
                'Export Date': new Date().toLocaleString()
            }
        });
        showMessage('PDF file exported successfully!', 'alert-success');
    } catch (error) {
        console.error('PDF export error:', error);
        showMessage('Failed to export PDF file. Please try again.', 'alert-danger');
    } finally {
        isExporting.value = false;
    }
};

const handleExportBoth = async () => {
    if (filteredData.value.length === 0) {
        showMessage('No data to export!', 'alert-warning');
        return;
    }

    try {
        isExporting.value = true;
        await exportToAll({
            filename: `data-export-${Date.now()}`,
            sheetName: 'Data Export',
            title: 'Sample Data Export',
            columns: exportColumns,
            data: filteredData.value,
            metadata: {
                'Total Records': filteredData.value.length,
                'Region Filter': selectedRegion.value || 'All',
                'Status Filter': selectedStatus.value || 'All',
                'Export Date': new Date().toLocaleString(),
                'Generated By': 'CBC BioMap Database System'
            }
        });
        showMessage('Excel and PDF files exported successfully!', 'alert-success');
    } catch (error) {
        console.error('Export error:', error);
        showMessage('Failed to export files. Please try again.', 'alert-danger');
    } finally {
        isExporting.value = false;
    }
};

// Helper functions
const resetFilters = () => {
    selectedRegion.value = '';
    selectedStatus.value = '';
};

const showMessage = (msg: string, cssClass: string) => {
    message.value = msg;
    messageClass.value = cssClass;
    setTimeout(() => {
        message.value = '';
    }, 5000);
};
</script>

<style scoped>
.export-example-component {
    padding: 20px;
}

.export-actions {
    display: flex;
    gap: 10px;
}

.table-responsive {
    max-height: 400px;
    overflow-y: auto;
}

button:disabled {
    opacity: 0.6;
    cursor: not-allowed;

