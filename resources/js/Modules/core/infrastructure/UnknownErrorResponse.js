export class UnknownErrorResponse extends Error{
    constructor( response = {} ) {
        super();
        this.title = response.title || 'Unknown Error';
        this.message = response.message || 'Something went wrong';
        this.type = 'failed';
        this.timeout = 5000;
        this.errno = response.errno || null;
        this.show = true;
        Object.assign(this, response.errors);
    }

    static fromObject(response) {
        return new UnknownErrorResponse(response.errors);
    }

    toObject() {
        return this;
    }
}
