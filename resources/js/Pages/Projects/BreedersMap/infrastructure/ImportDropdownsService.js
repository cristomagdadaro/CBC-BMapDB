import axios from "axios";
import { route } from "ziggy-js";
import { BreederType, EducLevel } from "@/Pages/constants";

export const getImportTemplateDropdowns = (model) => {
    if (!model) return {};

    const modelName = model.name || model.constructor?.name;
    switch (modelName) {
        case 'Commodity':
            return {
                breeder_id: [
                    async () => (await axios.get(route('api.breeders.index'), {
                        params: { paginate: false }
                    })).data.data.map((item) => item.id)
                ],
                name: [
                    async () => (await axios.get(route('api.breedersmap.commodities.priority.public'), {
                        params: { per_page: '*' }
                    })).data.data.map((item) => item.label)
                ],
                geolocation: [
                    async () => (await axios.get(route('api.cities.options.public'), {
                        params: { per_page: '*' }
                    })).data.data.map((item) => item.name)
                ]
            };
        case 'Breeder':
            return {
                breeder_type: BreederType.map(bt => bt.value),
                educ_level: EducLevel.map(bt => bt.value),
                geolocation: [
                    async () => (await axios.get(route('api.cities.options.public'), {
                        params: { per_page: '*' }
                    })).data.data.map((item) => item.name)
                ]
            };
        default:
            return {};
    }
};
