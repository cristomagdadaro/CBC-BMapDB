export class ForbiddenErrorResponse extends Error{
    constructor( response = {} ) {
        super();
        this.title = response.statusText || 'Caution';
        this.message = response.data.message || "You are not authorized";
        this.type = 'failed';
        this.timeout = 5000;
        this.errno = response.data.status || 403;
        this.show = true;
    }

    static fromObject(response) {
        return new ForbiddenErrorResponse(response.errors);
    }

    toObject() {
        return this;
    }
}
