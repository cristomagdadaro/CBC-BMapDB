export class NotFoundErrorResponse extends Error{
    constructor( response = {} ) {
        super();
        this.title = 'Warning';
        this.message = response.message || "Failed to delete, record not found";
        this.type = 'warning';
        this.timeout = 5000;
        this.errno = response.errno || null;
        this.show = true;
        Object.assign(this, response.errors);
    }

    static fromObject(response) {
        return new NotFoundErrorResponse(response.errors);
    }

    toObject() {
        return this;
    }
}
