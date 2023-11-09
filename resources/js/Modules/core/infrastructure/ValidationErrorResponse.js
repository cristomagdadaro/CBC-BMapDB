export class ValidationErrorResponse extends Error{
    constructor( response = {} ) {
        super();
        Object.assign(this, response.errors);
    }

    static fromObject(response) {
        return new ValidationErrorResponse(response.errors);
    }

    toObject() {
        return this;
    }
}
