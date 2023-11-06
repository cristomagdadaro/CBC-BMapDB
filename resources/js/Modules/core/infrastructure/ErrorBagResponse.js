export class ErrorBagResponse{
    constructor( response = {} ) {
        Object.assign(this, response.errors);
    }

    static fromObject(response) {
        return new ErrorBagResponse(response.errors);
    }

    toObject() {
        return this;
    }
}
