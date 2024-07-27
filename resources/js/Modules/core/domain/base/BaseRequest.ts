import DtoBaseRequest from "../../dto/base/DtoBaseRequest";

export default class BaseRequest extends DtoBaseRequest {

    constructor(params: DtoBaseRequest) {
        super(params);

        // optional parameters
        this.updateParam('search', params.search);
        this.updateParam('filter', params.filter);
        this.updateParam('is_exact', params.is_exact);
    }
}
