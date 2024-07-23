export class ForbiddenErrorResponse extends Error{
    constructor( response = {} ) {
        super();
        this.title = 'Warning';
        this.message = "Failed to execute, you're not authorized to perform this action";
        this.type = 'warning';
        this.timeout = 5000;
        this.errno = response.errno || null;
        this.show = true;
        Object.assign(this, response.errors);
    }

    static fromObject(response) {
        return new ForbiddenErrorResponse(response.errors);
    }

    toObject() {
        return this;
    }
}
