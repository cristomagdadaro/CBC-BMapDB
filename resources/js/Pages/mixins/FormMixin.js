import BaseCreateForm from "@/Components/Modal/BaseCreateForm.vue";
import TextField from "@/Components/Form/TextField.vue";
import SelectField from "@/Components/Form/SelectField.vue";
import SelectSearchField from "@/Components/Form/SelectSearchField.vue";
import BaseEditForm from "@/Components/Modal/BaseEditForm.vue";
import RadioField from "@/Components/Form/RadioField.vue";
import CancelButton from "@/Components/CRCMDatatable/Components/CancelButton.vue";
import CloseIcon from "@/Components/Icons/CloseIcon.vue";

export default {
    components: {
        BaseCreateForm,
        BaseEditForm,
        TextField,
        SelectField,
        RadioField,
        SelectSearchField,
        CancelButton,
        CloseIcon,
    },
    props: {
        errors: {
            type: Object,
            default: null
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
    methods: {
        resetForm() {
            this.form = Object.assign({}, this.data);
        },
        getError(name) {
            return this.errors ? this.errors[name] : null;
        },
        close() {
            this.form.map((key, value) => {
                this.form[key] = null;
            });

            this.emitClose();
        },
        emitClose() {
            this.$emit('close');
        }
    },
    watch: {
        forceClose() {
            this.resetForm();
            this.emitClose();
        },
        data() {
            this.form = Object.assign({}, this.data);
        }
    },
}
