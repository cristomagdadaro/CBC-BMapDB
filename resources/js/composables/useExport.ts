import { ExportService, ExportColumn, ExportConfig } from '@/utils/ExportService';

export function useExport() {
    /**
     * Export data to Excel
     */
    const exportToExcel = async (config: ExportConfig) => {
        try {
            await ExportService.exportToExcel(config);
        } catch (error) {
            console.error('Export to Excel failed:', error);
            throw error;
        }
    };

    /**
     * Export data to PDF
     */
    const exportToPDF = (config: ExportConfig) => {
        try {
            ExportService.exportToPDF(config);
        } catch (error) {
            console.error('Export to PDF failed:', error);
            throw error;
        }
    };

    /**
     * Export data to both Excel and PDF
     */
    const exportToAll = async (config: ExportConfig) => {
        try {
            await exportToExcel(config);
            exportToPDF(config);
        } catch (error) {
            console.error('Export failed:', error);
            throw error;
        }
    };

    return {
        exportToExcel,
        exportToPDF,
        exportToAll
    };
}
