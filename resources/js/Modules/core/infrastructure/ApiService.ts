import axios from "axios";
import BaseResponse from "@/Modules/core/domain/base/BaseResponse";
import {ValidationErrorResponse} from "@/Modules/core/domain/response/ValidationErrorResponse";
import {NotFoundErrorResponse} from "@/Modules/core/domain/response/NotFoundErrorResponse";
import {ServerErrorResponse} from "@/Modules/core/domain/response/ServerErrorResponse";
import {JavascriptErrorResponse} from "@/Modules/core/domain/response/JavascriptErrorResponse";
import {ForbiddenErrorResponse} from "@/Modules/core/domain/response/ForbiddenErrorResponse";
import {Ref, ref} from "vue";
import IApiService from "../interface/IApiService";
import DtoError from "../dto/base/DtoError";

export default class ApiService implements IApiService
{
    _processing: boolean;
    _baseUrl: string;
    _errorBag: Ref<DtoError>  = ref();

    constructor(url: string) {
        this._processing = false;
        this._baseUrl = url;
    }

    get processing() {
        return this._processing;
    }

    set processing(value) {
        this._processing = value;
    }

    set baseUrl(url) {
        this._baseUrl = url;
    }

    get baseUrl() {
        return this._baseUrl;
    }

    async get(params, model = undefined)
    {
        try {
            this._processing = true;
            const response = await axios.get(this.baseUrl, {
                params: params
            });

            if (model) {
                response.data.data = this.castToModel(response.data.data, model);
                return new BaseResponse(response.data);
            }

            return new BaseResponse(response);
        } catch (error) {
            return this.determineError(error);
        } finally {
            this._processing = false;
        }
    }

    async post(data)
    {
        try {
            this._processing = true;
            const response = await axios.post(this.baseUrl, data);
            return new BaseResponse(response.data);
        } catch (error) {
            return this.determineError(error);
        } finally {
            this._processing = false;
        }
    }

    async put(data)
    {
        try {
            this._processing = true;
            const response = await axios.put(this.baseUrl + '/' + data.id, data);
            return new BaseResponse(response.data);
        } catch (error) {
            return this.determineError(error);
        } finally {
            this._processing = false;
        }
    }

    async delete(id)
    {
        try {
            this._processing = true;
            if (id.length > 1){
                const response = await axios.delete(this.baseUrl + '/delete',
                    {
                        params: {
                            ids: id
                        }
                    });
                return new BaseResponse(response.data);
            }else{
                const response = await axios.delete(this.baseUrl + '/' + id);
                return new BaseResponse(response.data);
            }
        } catch (error) {
            return this.determineError(error);
        } finally {
            this._processing = false;
        }
    }

    castToModel(response, model) {
        return response.map(item => {
            return new model(item);
        });
    }

    determineError(error: any): DtoError
    {
        let errorResponse = new JavascriptErrorResponse(error);
        if(error.response)
            switch (error.response.status) {
                case 422:
                    errorResponse = new ValidationErrorResponse(error.response.data);
                    break;
                case 403:
                    errorResponse = new ForbiddenErrorResponse(error.response.data);
                    break;
                case 404:
                    errorResponse = new NotFoundErrorResponse(error.response.data);
                    break;
                default:
                    errorResponse = new ServerErrorResponse(error.response.data);
            }
        //@ts-ignore
        this._errorBag = errorResponse;
        return errorResponse;
    }
}
