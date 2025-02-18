import ApiService from "@/Modules/core/infrastructure/ApiService";
import BaseRequest from "@/Modules/core/domain/base/BaseRequest";
import BaseResponse from "@/Modules/core/domain/base/BaseResponse";
import {AxiosResponse} from "axios";
import DtoError from "@/Modules/core/dto/base/DtoError";

export default class OpenAiApiService {
    api: ApiService;
    model: Object;
    request: BaseRequest;
    response: BaseResponse | AxiosResponse | DtoError;

    constructor(baseUrl: string, model = Object) {
        this.api = new ApiService(baseUrl);
        this.model = model;

        const localParams = BaseRequest.getParamsLocal();
        this.request = localParams? new BaseRequest(localParams) : new BaseRequest();
    }

    async getChatResponse(params: any) {
        return await this.api.post(params);
    }
}
