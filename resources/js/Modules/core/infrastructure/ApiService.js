import axios from "axios";
import BaseResponse from "@/Modules/core/infrastructure/BaseResponse.js";
import {ValidationErrorResponse} from "@/Modules/core/infrastructure/ValidationErrorResponse.js";
import {NotFoundErrorResponse} from "@/Modules/core/infrastructure/NotFoundErrorResponse.js";
import {ServerErrorResponse} from "@/Modules/core/infrastructure/ServerErrorResponse.js";
import {JavascriptErrorResponse} from "@/Modules/core/infrastructure/JavascriptErrorResponse.js";

export default class ApiService
{
    constructor(url) {
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

    determineError(error)
    {
        if(error.response)
            switch (error.response.status) {
                case 422:
                    return new ValidationErrorResponse(error.response.data);
                case 404:
                    return new NotFoundErrorResponse(error.response.data);
                default:
                    return new ServerErrorResponse(error.response.data);
            }
        return new JavascriptErrorResponse(error);
    }
}

