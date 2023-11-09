export class NotFoundErrorResponse extends Error{
    constructor( response = {} ) {
        super();
        Object.assign(this, response.errors);
    }

    static fromObject(response) {
        return new NotFoundErrorResponse(response.errors);
    }

    toObject() {
        return this;
    }
}
