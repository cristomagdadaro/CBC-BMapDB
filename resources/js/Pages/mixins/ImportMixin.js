import Papa from "papaparse";
import ExcelJS from "exceljs";
import BaseCreateForm from "@/Components/Modal/BaseCreateForm.vue";
import SelectSearchField from "@/Components/Form/SelectSearchField.vue";
import RadioField from "@/Components/Form/RadioField.vue";
import BaseButton from "@/Components/CRCMDatatable/Components/BaseButton.vue";
import FileField from "@/Components/Form/FileField.vue";


export default {
    components: { FileField, BaseButton, RadioField, SelectSearchField, BaseCreateForm },
    data() {
        return {
            parsing: false,
            form: null,
            uploadFile: null,
            model: null,
        };
    },
    computed: {
        headers() {
            // Use sanitized headers that exclude auto-generated and non-import fields
            return this.model.importTemplateHeaders();
        },
        dropdowns() {
            // Optional dropdown lists per column for Excel data validation
            return this.model.importTemplateDropdowns();
        },
        xlsxFileName() {
            // date_stamped filename
            const date = new Date();
            const y = date.getFullYear();
            const m = String(date.getMonth() + 1).padStart(2, '0');
            const d = String(date.getDate()).padStart(2, '0');
            return `import_template_${y}${m}${d}.xlsx`;
        }
    },
    props: {
        errors: {
            type: Object,
            default: () => ({}),
        },
        forceClose: {
            type: Boolean,
            default: false,
        },
        data: {
            type: Object,
            default: null,
        },
    },
    methods: {
        close() {
            this.$emit('close');
        },
        resetForm() {
            this.form = Object.assign({}, this.data);
            this.$emit('close');
        },
        uploadForm() {
            this.$emit('uploadForm', this.form.data);
        },
        async handleFileUpload(event) {
            const file = event.target.files[0];
            if (!file) return;
            await this.importFile(file);
        },
        async importFile(file) {
            const name = (file.name || '').toLowerCase();
            const type = (file.type || '').toLowerCase();
            const isExcel = name.endsWith('.xlsx') || name.endsWith('.xls') || type.includes('sheet') || type.includes('excel');
            if (isExcel) {
                return this.importXLSX(file);
            }
            // fallback to CSV
            return this.importCSV(file);
        },
        importCSV(file) {
            const reader = new FileReader();
            reader.onload = (event) => {
                const csvData = event.target.result;
                Papa.parse(csvData, {
                    header: true,
                    skipEmptyLines: true,
                    complete: (results) => {
                        const allowedHeaders = Array.isArray(this.headers) ? this.headers : null;
                        const sanitizedRows = (results.data || [])
                            .filter(row => row && Object.values(row).some(v => String(v ?? '').trim() !== ''))
                            .map(row => {
                                if (!allowedHeaders) return row;
                                const cleaned = {};
                                for (const key of allowedHeaders) {
                                    if (Object.prototype.hasOwnProperty.call(row, key)) {
                                        cleaned[key] = row[key];
                                    }
                                }
                                return cleaned;
                            });
                        this.form = { ...results, data: sanitizedRows };
                    },
                    error: (error) => {
                        console.error('Error parsing CSV:', error);
                    },
                });
            };
            reader.readAsText(file);
        },
        async importXLSX(file) {
            try {
                const reader = new FileReader();
                reader.onload = async (e) => {
                    try {
                        const buffer = e.target.result;
                        const workbook = new ExcelJS.Workbook();
                        await workbook.xlsx.load(buffer);

                        // Prefer a sheet named 'Template', else use the first visible sheet
                        let ws = workbook.getWorksheet('Template');
                        if (!ws) {
                            ws = workbook.worksheets.find(s => s && s.state !== 'veryHidden' && s.state !== 'hidden') || workbook.worksheets[0];
                        }
                        if (!ws) throw new Error('No worksheet found in uploaded file.');

                        // Read header row (first row)
                        const headerRow = ws.getRow(1);
                        const headers = headerRow.values
                            .filter((v, idx) => idx !== 0) // exceljs rows are 1-indexed; values[0] is null
                            .map(v => String(v || '').trim())
                            .filter(v => v.length);

                        const allowedHeaders = Array.isArray(this.headers) ? this.headers : headers;

                        // Read data rows from row 2 downwards
                        const rows = [];
                        for (let r = 2; r <= ws.rowCount; r++) {
                            const row = ws.getRow(r);
                            if (!row || row.cellCount === 0) continue;
                            // Build object by matching header order
                            const obj = {};
                            let hasValue = false;
                            headers.forEach((h, idx) => {
                                const cell = row.getCell(idx + 1);
                                const val = cell && (cell.text ?? cell.value);
                                const text = typeof val === 'object' && val && 'text' in val ? String(val.text ?? '') : String(val ?? '');
                                if (text.trim().length) hasValue = true;
                                if (allowedHeaders.includes(h)) {
                                    obj[h] = text;
                                }
                            });
                            if (hasValue) rows.push(obj);
                        }

                        this.form = { data: rows };
                    } catch (err) {
                        console.error('Error parsing XLSX:', err);
                    }
                };
                reader.readAsArrayBuffer(file);
            } catch (e) {
                console.error('Failed to read Excel file:', e);
            }
        },
        downloadCsvTemplate(headers, fileName = 'template.csv') {
            const csvHeader = headers.join(',');
            const csvContent = `${csvHeader}\r\n`;
            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement('a');
            const url = URL.createObjectURL(blob);

            link.setAttribute('href', url);
            link.setAttribute('download', fileName);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            URL.revokeObjectURL(url);
        },
        async downloadExcelTemplate(headers, fileName = 'template.xlsx', dropdowns = {}) {
            // If caller passed a Promise, await it
            if (dropdowns && typeof dropdowns.then === 'function') {
                try { dropdowns = await dropdowns; } catch (e) { dropdowns = {}; }
            }
            // Resolve dropdowns that may be arrays, functions, or promises
            async function resolveDropdowns(raw) {
                const out = {};
                if (!raw || typeof raw !== 'object') return out;
                const keys = Object.keys(raw);
                for (const key of keys) {
                    let val = raw[key];
                    // Function returning promise/array
                    if (typeof val === 'function') {
                        val = await val();
                    } else if (val && typeof val.then === 'function') {
                        // Promise
                        val = await val;
                    } else if (Array.isArray(val)) {
                        const acc = [];
                        for (const item of val) {
                            if (typeof item === 'function') {
                                const res = await item();
                                if (Array.isArray(res)) acc.push(...res); else acc.push(res);
                            } else if (item && typeof item.then === 'function') {
                                const res = await item;
                                if (Array.isArray(res)) acc.push(...res); else acc.push(res);
                            } else if (item !== null && item !== undefined) {
                                acc.push(item);
                            }
                        }
                        val = acc;
                    }
                    // Ensure array of strings
                    if (Array.isArray(val)) {
                        val = val
                            .filter(v => v !== null && v !== undefined)
                            .map(v => String(v));
                    } else if (val !== undefined && val !== null) {
                        val = [String(val)];
                    } else {
                        val = [];
                    }
                    out[key] = val;
                }
                return out;
            }

            const resolvedDropdowns = await resolveDropdowns(dropdowns);

            const workbook = new ExcelJS.Workbook();
            const ws = workbook.addWorksheet('Template');

            // Add header row
            ws.addRow(headers);
            const headerRow = ws.getRow(1);
            headerRow.font = { bold: true };
            headerRow.alignment = { vertical: 'middle', horizontal: 'center' };
            headerRow.height = 18;

            // Set all columns to Text format and reasonable widths
            headers.forEach((h, idx) => {
                const col = ws.getColumn(idx + 1);
                col.numFmt = '@'; // force Text
                col.alignment = { horizontal: 'left' };
                const width = Math.min(Math.max(String(h).length + 5, 12), 40);
                col.width = width;
            });

            // If dropdowns are provided, create a hidden sheet with list values and set validations
            const keysWithDropdowns = Object.keys(resolvedDropdowns || {}).filter(k => Array.isArray(resolvedDropdowns[k]) && resolvedDropdowns[k].length);
            let listsSheet = null;
            if (keysWithDropdowns.length) {
                listsSheet = workbook.addWorksheet('Lists');
                listsSheet.state = 'veryHidden';

                // Build each list in its own column and keep a map of ranges
                const ranges = {};
                keysWithDropdowns.forEach((key, colIdx) => {
                    const values = resolvedDropdowns[key].filter(v => v !== null && v !== undefined).map(v => String(v));
                    if (!values.length) return;
                    // Write header for clarity (optional)
                    listsSheet.getCell(1, colIdx + 1).value = key;
                    values.forEach((val, r) => listsSheet.getCell(r + 2, colIdx + 1).value = val);

                    // Range starts at row 2 to skip the header label
                    const startCell = listsSheet.getCell(2, colIdx + 1).address;
                    const endCell = listsSheet.getCell(values.length + 1, colIdx + 1).address;
                    ranges[key] = `Lists!$${startCell.replace(/\d+/, '2')}:$${endCell}`;
                });

                // Apply validations to Template sheet for each matching header
                headers.forEach((h, hIdx) => {
                    const key = String(h);
                    if (!ranges[key]) return;
                    const colLetter = ws.getColumn(hIdx + 1).letter;
                    const address = `${colLetter}2:${colLetter}1000`; // rows 2..1000
                    ws.dataValidations.add(address, {
                        type: 'list',
                        allowBlank: true,
                        showErrorMessage: true,
                        errorStyle: 'warning',
                        errorTitle: 'Invalid Selection',
                        error: 'Please select a value from the list.',
                        formulae: [ `=${ranges[key]}` ],
                    });
                });
            }

            // Generate and trigger download
            const buffer = await workbook.xlsx.writeBuffer();
            const blob = new Blob([buffer], { type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = fileName;
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            URL.revokeObjectURL(url);
        },
    },
    watch: {
        forceClose() {
            this.close();
        },
        data() {
            this.form = Object.assign({}, this.data);
        },
    },
};
