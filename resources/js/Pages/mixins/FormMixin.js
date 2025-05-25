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
import ApiService from "@/Modules/core/infrastructure/ApiService";

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
        processing: {
            type: Boolean,
            default: false
        }
    },
    methods: {
        resetForm() {
            this.form = this.model.updateForm(this.data); // Get only fields needed for update
        },
        getTitle(name) {
            if (this.data)
                return this.model.getUpdateFieldTitle(name);
            else
                return this.model.getCreateFieldTitle(name);
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
        },
        async getCustomSelectionOptions(url){
            return await (new ApiService(url)).get();
        },
        resizedataURL(datas, wantedWidth, wantedHeight, callback) {
            var img = new Image();

            img.onload = function () {
                var canvas = document.createElement('canvas');
                var ctx = canvas.getContext('2d');

                canvas.width = wantedWidth;
                canvas.height = wantedHeight;

                ctx.drawImage(this, 0, 0, wantedWidth, wantedHeight);

                let quality = 0.8; // Start with high quality
                let compressedDataUrl = canvas.toDataURL('image/jpeg', quality);

                // Reduce quality if still over 2MB
                function checkSize(dataUrl) {
                    let byteSize = Math.ceil((dataUrl.length - 'data:image/jpeg;base64,'.length) * 3 / 4);
                    console.log(byteSize)
                    if (byteSize > 2 * 1024 * 1024) {
                        quality -= 0.05;
                        if (quality > 0) {
                            compressedDataUrl = canvas.toDataURL('image/jpeg', quality);
                            checkSize(compressedDataUrl);
                        }
                    }
                }
                checkSize(compressedDataUrl);
                callback(compressedDataUrl); // Return final compressed image
            };
            img.src = datas;
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
        },
        'form.photo': {
            handler: function (newVal) {
                if (!newVal) return;

                const reader = new FileReader();

                reader.onload = (e) => {
                    this.resizedataURL(e.target.result, 960/2, 540/2, (compressedDataUrl) => {
                        this.form.photo = compressedDataUrl;
                    });
                };

                if (newVal instanceof File) {
                    reader.readAsDataURL(newVal);
                }
            },
            deep: true
        }
    },
    computed: {
        User() {
            return User
        },
    },
}
