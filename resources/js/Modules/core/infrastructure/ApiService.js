import axios from "axios";
import BaseResponse from "@/Modules/core/infrastructure/BaseResponse.js";
import { BaseClass } from "@/Modules/core/domain/BaseClass.js";
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

    castToModel(response, model) {
        return response.map(item => {
            return new model(item);
        });
    }
}
