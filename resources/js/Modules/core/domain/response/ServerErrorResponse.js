export class ServerErrorResponse extends Error{
    constructor( response = {} ) {
        super();
        this.title = response.title || 'Internal Server Error';
        this.message = response.message || 'Something went wrong';
        this.type = 'warning';
        this.timeout = response.timeout || 5000;
        this.show = response.show || true;
        this.errno = 500;
        Object.assign(this, response.errors);
    }

    static fromObject(response) {
        return new ServerErrorResponse(response.errors);
    }

    toObject() {
        return this;
    }
}
