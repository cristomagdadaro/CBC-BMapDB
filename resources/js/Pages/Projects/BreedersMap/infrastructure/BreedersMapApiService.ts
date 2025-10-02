import ApiService from "@/Modules/core/infrastructure/ApiService";

export default class BreedersMapApiService {
    apiService: ApiService;

    constructor(baseUrl: string) {
        this.apiService = new ApiService(baseUrl);
    }
}
