import ExcelJS from 'exceljs';
import jsPDF from 'jspdf';
import autoTable from 'jspdf-autotable';

export interface ExportColumn {
    header: string;
    key: string;
    width?: number;
}

export interface ExportConfig {
    filename: string;
    sheetName?: string;
    title?: string;
    columns: ExportColumn[];
    data: any[];
    metadata?: Record<string, any>;
}

export class ExportService {
    /**
     * Export data to Excel file
     * @param config Export configuration
     */
    static async exportToExcel(config: ExportConfig): Promise<void> {
        const {
            filename,
            sheetName = 'Sheet1',
            title,
            columns,
            data,
            metadata
        } = config;

        try {
            // Create a new workbook
            const workbook = new ExcelJS.Workbook();
            workbook.creator = 'CBC BioMap Database';
            workbook.created = new Date();
            workbook.modified = new Date();

            // Add a worksheet
            const worksheet = workbook.addWorksheet(sheetName);

            let currentRow = 1;

            // Add title if provided
            if (title) {
                worksheet.mergeCells(`A${currentRow}:${this.getColumnLetter(columns.length)}${currentRow}`);
                const titleCell = worksheet.getCell(`A${currentRow}`);
                titleCell.value = title;
                titleCell.font = { size: 16, bold: true };
                titleCell.alignment = { vertical: 'middle', horizontal: 'center' };
                titleCell.fill = {
                    type: 'pattern',
                    pattern: 'solid',
                    fgColor: { argb: 'FF4472C4' }
                };
                titleCell.font = { ...titleCell.font, color: { argb: 'FFFFFFFF' } };
                worksheet.getRow(currentRow).height = 30;
                currentRow += 2;
            }

            // Add metadata if provided
            if (metadata) {
                Object.entries(metadata).forEach(([key, value]) => {
                    const row = worksheet.getRow(currentRow);
                    row.getCell(1).value = key;
                    row.getCell(1).font = { bold: true };
                    row.getCell(2).value = value;
                    currentRow++;
                });
                currentRow++;
            }

            // Define columns
            worksheet.columns = columns.map(col => ({
                header: col.header,
                key: col.key,
                width: col.width || 15
            }));

            // Style header row
            const headerRow = worksheet.getRow(currentRow);
            headerRow.values = columns.map(col => col.header);
            headerRow.font = { bold: true, color: { argb: 'FFFFFFFF' } };
            headerRow.fill = {
                type: 'pattern',
                pattern: 'solid',
                fgColor: { argb: 'FF2E75B6' }
            };
            headerRow.alignment = { vertical: 'middle', horizontal: 'center' };
            headerRow.height = 20;

            // Add borders to header
            headerRow.eachCell((cell) => {
                cell.border = {
                    top: { style: 'thin' },
                    left: { style: 'thin' },
                    bottom: { style: 'thin' },
                    right: { style: 'thin' }
                };
            });

            currentRow++;

            // Add data rows
            data.forEach((item, index) => {
                const row = worksheet.getRow(currentRow);
                columns.forEach((col, colIndex) => {
                    const cell = row.getCell(colIndex + 1);
                    cell.value = this.getNestedValue(item, col.key);
                    cell.border = {
                        top: { style: 'thin' },
                        left: { style: 'thin' },
                        bottom: { style: 'thin' },
                        right: { style: 'thin' }
                    };

                    // Alternate row colors
                    if (index % 2 === 0) {
                        cell.fill = {
                            type: 'pattern',
                            pattern: 'solid',
                            fgColor: { argb: 'FFF2F2F2' }
                        };
                    }
                });
                currentRow++;
            });

            // Auto-fit columns
            worksheet.columns.forEach(column => {
                if (!column.width) {
                    let maxLength = 0;
                    column.eachCell({ includeEmpty: true }, (cell) => {
                        const columnLength = cell.value ? cell.value.toString().length : 10;
                        if (columnLength > maxLength) {
                            maxLength = columnLength;
                        }
                    });
                    column.width = maxLength < 10 ? 10 : maxLength + 2;
                }
            });

            // Generate Excel file and download
            const buffer = await workbook.xlsx.writeBuffer();
            const blob = new Blob([buffer], {
                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            });
            this.downloadFile(blob, `${filename}.xlsx`);
        } catch (error) {
            console.error('Error exporting to Excel:', error);
            throw new Error('Failed to export to Excel');
        }
    }

    /**
     * Export data to PDF file
     * @param config Export configuration
     */
    static exportToPDF(config: ExportConfig): void {
        const {
            filename,
            title,
            columns,
            data,
            metadata
        } = config;

        try {
            // Create a new PDF document
            const doc = new jsPDF({
                orientation: columns.length > 6 ? 'landscape' : 'portrait',
                unit: 'mm',
                format: 'a4'
            });

            let yPosition = 20;

            // Add title
            if (title) {
                doc.setFontSize(18);
                doc.setFont('helvetica', 'bold');
                doc.text(title, doc.internal.pageSize.getWidth() / 2, yPosition, { align: 'center' });
                yPosition += 10;
            }

            // Add metadata
            if (metadata) {
                doc.setFontSize(10);
                doc.setFont('helvetica', 'normal');
                Object.entries(metadata).forEach(([key, value]) => {
                    doc.text(`${key}: ${value}`, 14, yPosition);
                    yPosition += 6;
                });
                yPosition += 5;
            }

            // Prepare table data
            const headers = columns.map(col => col.header);
            const tableData = data.map(item =>
                columns.map(col => {
                    const value = this.getNestedValue(item, col.key);
                    return value !== null && value !== undefined ? String(value) : '';
                })
            );

            // Generate table
            autoTable(doc, {
                startY: yPosition,
                head: [headers],
                body: tableData,
                theme: 'striped',
                headStyles: {
                    fillColor: [46, 117, 182],
                    textColor: [255, 255, 255],
                    fontStyle: 'bold',
                    halign: 'center'
                },
                alternateRowStyles: {
                    fillColor: [242, 242, 242]
                },
                styles: {
                    fontSize: 9,
                    cellPadding: 3,
                    overflow: 'linebreak',
                    cellWidth: 'wrap'
                },
                columnStyles: columns.reduce((acc, col, index) => {
                    if (col.width) {
                        acc[index] = { cellWidth: col.width };
                    }
                    return acc;
                }, {}),
                margin: { top: 10, right: 14, bottom: 10, left: 14 },
                didDrawPage: (data) => {
                    // Footer
                    const pageCount = doc.getNumberOfPages();
                    doc.setFontSize(8);
                    doc.setTextColor(150);
                    doc.text(
                        `Page ${data.pageNumber} of ${pageCount}`,
                        doc.internal.pageSize.getWidth() / 2,
                        doc.internal.pageSize.getHeight() - 10,
                        { align: 'center' }
                    );
                    doc.text(
                        `Generated on ${new Date().toLocaleString()}`,
                        14,
                        doc.internal.pageSize.getHeight() - 10
                    );
                }
            });

            // Save the PDF
            doc.save(`${filename}.pdf`);
        } catch (error) {
            console.error('Error exporting to PDF:', error);
            throw new Error('Failed to export to PDF');
        }
    }

    /**
     * Get nested object value by path
     * @param obj Object to search
     * @param path Dot-separated path (e.g., 'user.name')
     */
    private static getNestedValue(obj: any, path: string): any {
        return path.split('.').reduce((current, prop) =>
            current?.[prop], obj
        );
    }

    /**
     * Convert column index to Excel column letter
     * @param num Column index (1-based)
     */
    private static getColumnLetter(num: number): string {
        let letter = '';
        while (num > 0) {
            const remainder = (num - 1) % 26;
            letter = String.fromCharCode(65 + remainder) + letter;
            num = Math.floor((num - 1) / 26);
        }
        return letter;
    }

    /**
     * Download a blob as a file
     * @param blob Blob to download
     * @param filename Filename for download
     */
    private static downloadFile(blob: Blob, filename: string): void {
        const url = window.URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = filename;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
        window.URL.revokeObjectURL(url);
    }

    /**
     * Export chart to image and include in PDF
     * @param chartCanvas Canvas element of the chart
     */
    static getChartImage(chartCanvas: HTMLCanvasElement): string {
        return chartCanvas.toDataURL('image/png');
    }
}

