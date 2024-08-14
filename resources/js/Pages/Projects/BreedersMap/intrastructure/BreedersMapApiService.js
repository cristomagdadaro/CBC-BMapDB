import ApiService from "@/Modules/core/infrastructure/ApiService.js";

export default class BreedersMapApiService {
  constructor(baseUrl) {
    this.apiService = new ApiService(baseUrl);
  }

  /**
   * Get the list of breeders for the searchable dropwdown list in create and update commodity forms.
   * */
  async getListOfBreeders() {
    return await this.apiService.get();
  }
}
