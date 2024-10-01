import DtoBaseClass from "../../dto/base/DtoBaseClass";
import IBaseClass from "../../interface/base/IBaseClass";

export default class BaseClass extends DtoBaseClass implements IBaseClass
{
    _indexUri?: string;
    _showUri?: string;
    _storeUri?: string;
    _updateUri?: string;
    _destroyUri?: string;
    _multiDestroyUri?: string;

    constructor(resp = {}) {
        super(resp);
    }
    set indexUri(value: string) {
        this._indexUri = value;
    }


    set showUri(value: string) {
        this._showUri = value;
    }

    set storeUri(value: string) {
        this._storeUri = value;
    }

    set updateUri(value: string) {
        this._updateUri = value;
    }

    set destroyUri(value: string) {
        this._destroyUri = value;
    }

    set multiDestroyUri(value: string) {
        this._multiDestroyUri = value;
    }

    static get indexUri(): string {
        return new this({})._indexUri;
    }

    static get showUri() {
        return new this({})._showUri;
    }

    static get storeUri() {
        return new this({})._storeUri;
    }
    static get updateUri() {
        return new this({})._updateUri;
    }

    static get destroyUri() {
        return new this({})._destroyUri;
    }

    static get multiDestroyUri() {
        return new this({})._multiDestroyUri;
    }
}
