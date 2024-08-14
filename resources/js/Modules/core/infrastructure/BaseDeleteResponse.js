export class BaseDeleteResponse extends Error{
    constructor( response = {} ) {
        super();
        this.title = response.title;
        this.message = response.message
        this.type = response.type;
        this.timeout = response.timeout || 5000;
        this.show = true;
        Object.assign(this, response.errors);
    }

    static fromObject(response) {
        return new BaseDeleteResponse(response.errors);
    }

    toObject() {
        return this;
    }
}
