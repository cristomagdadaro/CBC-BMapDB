import axios from "axios";
import BaseResponse from "@/Modules/core/infrastructure/BaseResponse.js";

export default class ApiService
{
    constructor(url) {
        this.baseUrl = url;
    }

    setBaseUrl(url) {
        this.baseUrl = url;
    }

    async get(params = {})
    {
        try {
            const response = await axios.get(this.baseUrl, {
                params: params
            });
            return new BaseResponse(response.data);
        } catch (error) {
            throw new Error(error);
        }
    }


}
