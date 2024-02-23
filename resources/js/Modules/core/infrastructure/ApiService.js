import axios from "axios";
import BaseResponse from "@/Modules/core/infrastructure/BaseResponse.js";
import {ValidationErrorResponse} from "@/Modules/core/infrastructure/ValidationErrorResponse.js";
import {NotFoundErrorResponse} from "@/Modules/core/infrastructure/NotFoundErrorResponse.js";
import {ServerErrorResponse} from "@/Modules/core/infrastructure/ServerErrorResponse.js";
export default class ApiService
{
    constructor(url) {
        this.processing = false;
        this.baseUrl = url;
    }

    setBaseUrl(url) {
        this.baseUrl = url;
    }

    async get(params, model = undefined)
    {
        try {
            this.processing = true;
            const response = await axios.get(this.baseUrl, {
                params: params
            });

            if (model !== undefined) {
                response.data.data = this.castToModel(response.data.data, model);
                return new BaseResponse(response.data);
            }
            return response;
        } catch (error) {
            console.log(error);
            return this.determineError(error);
        } finally {
            this.processing = false;
        }
    }

    async post(data)
    {
        try {
            this.processing = true;
            const response = await axios.post(this.baseUrl, data);
            return new BaseResponse(response.data);
        } catch (error) {
            return this.determineError(error);
        } finally {
            this.processing = false;
        }
    }

    async put(data)
    {
        try {
            this.processing = true;
            const response = await axios.put(this.baseUrl + '/' + data.id, data);
            return new BaseResponse(response.data);
        } catch (error) {
            return this.determineError(error);
        } finally {
            this.processing = false;
        }
    }

    async delete(id)
    {
        try {
            this.processing = true;
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
            this.processing = false;
        }
    }

    castToModel(response, model) {
        return response.map(item => {
            return new model(item);
        });
    }

    determineError(error)
    {
        switch (error.response.status) {
            case 422:
                return new ValidationErrorResponse(error.response.data);
            case 404:
                return new NotFoundErrorResponse(error.response.data);
            case 500:
                return new ServerErrorResponse(error.response.data);
            default:
                throw new Error(error);
        }
    }
}

