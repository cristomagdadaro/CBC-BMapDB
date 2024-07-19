export class ValidationErrorResponse extends Error{
    constructor( response = {} ) {
        super();
        this.title = 'Warning';
        this.message = response.message || "Validation error, check input fields";
        this.type = 'warning';
        this.timeout = 5000;
        this.show = true;
        Object.assign(this, response.errors);
    }

    static fromObject(response) {
        return new ValidationErrorResponse(response.errors);
    }

    toObject() {
        return this;
    }
}
