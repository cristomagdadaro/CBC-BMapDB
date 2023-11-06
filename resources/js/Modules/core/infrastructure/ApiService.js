import axios from "axios";
import BaseResponse from "@/Modules/core/infrastructure/BaseResponse.js";
import { BaseClass } from "@/Modules/core/domain/BaseClass.js";
import {ErrorBagResponse} from "@/Modules/core/infrastructure/ErrorBagResponse.js";
export default class ApiService
{
    constructor(url) {
        this.baseUrl = url;
    }

    setBaseUrl(url) {
        this.baseUrl = url;
    }

    async get(params = {}, model = BaseClass)
    {
        try {
            const response = await axios.get(this.baseUrl, {
                params: params
            });
            response.data.data = this.castToModel(response.data.data, model);
            return new BaseResponse(response.data);
        } catch (error) {
            throw new Error(error);
        }
    }

    async post(data)
    {
        try {
            const response = await axios.post(this.baseUrl, data);
            return new BaseResponse(response.data);
        } catch (error) {
            if (error.response.status === 422)
                return new ErrorBagResponse(error.response.data);
            throw new Error(error);
        }
    }

    async put(data)
    {
        try {
            const response = await axios.put(this.baseUrl, data);
            return new BaseResponse(response.data);
        } catch (error) {
            throw new Error(error);
        }
    }

    async delete(id)
    {
        try {
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
            console.log(error);
            //throw new Error(error);
        }
    }

    castToModel(response, model) {
        return response.map(item => {
            return new model(item);
        });
    }
}

