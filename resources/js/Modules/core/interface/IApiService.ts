import {Ref} from "vue";
import DtoError from "../dto/base/DtoError";

export default interface IApiService {
    _processing: Ref<boolean>;
    _baseUrl: string;
    _errorBag: Ref<DtoError>;
}
