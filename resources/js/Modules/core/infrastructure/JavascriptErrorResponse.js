export class JavascriptErrorResponse extends Error{
    constructor( response = {} ) {
        super();
        this.title = 'Error!';
        this.message = response.message || "System error, contact the administrator.";
        this.type = 'error';
        this.timeout = 5000;
        this.show = true;
        Object.assign(this, response.errors);
    }

    static fromObject(response) {
        return new JavascriptErrorResponse(response.errors);
    }

    toObject() {
        return this;
    }
}
