import BaseCreateForm from "@/Components/Modal/BaseCreateForm.vue";
import TextField from "@/Components/Form/TextField.vue";
import SelectField from "@/Components/Form/SelectField.vue";
import SelectSearchField from "@/Components/Form/SelectSearchField.vue";
import SelectSearchFieldV2 from "@/Components/Form/SelectSearchFieldV2.vue";
import BaseEditForm from "@/Components/Modal/BaseEditForm.vue";
import RadioField from "@/Components/Form/RadioField.vue";
import CancelButton from "@/Components/CRCMDatatable/Components/CancelButton.vue";
import CloseIcon from "@/Components/Icons/CloseIcon.vue";
import BaseClass from "@/Modules/core/domain/base/BaseClass";
import User from "@/Modules/core/domain/auth/User";

export default {
    components: {
        BaseCreateForm,
        BaseEditForm,
        TextField,
        SelectField,
        RadioField,
        SelectSearchField,
        SelectSearchFieldV2,
        CancelButton,
        CloseIcon,
    },
    data() {
        return {
            form: {},
            model: BaseClass,
        };
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
        },
        id: {
            type: Number,
            default: null
        },
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
        },
        isAdmin(){
            return (new this.User(this.$page.props.auth.user)).isAdmin;
        }
    },
    watch: {
        forceClose() {
            this.resetForm();
            this.emitClose();
        },
        data(newVal) {
            if (this.data)
                this.form = this.model.updateForm(newVal);
            else
                this.form = this.model.createForm(newVal);
        }
    },
    computed: {
        User() {
            return User
        },
    }
}
