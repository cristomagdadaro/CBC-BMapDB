import Papa from "papaparse";

export default {
    data() {
        return {
            parsing: false,
            form: {
                csvContent: null,
            },
        };
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
            this.$emit('uploadForm', this.form.csvContent);
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
                        this.form.csvContent = results;
                    },
                    error: (error) => {
                        console.error('Error parsing CSV:', error);
                    },
                });
            };
            reader.readAsText(file);
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
