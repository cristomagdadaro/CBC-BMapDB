export default interface IBaseClass {
    table?: string;
    updated_at?: string;
    created_at?: string;
    deleted_at?: string;

    _indexUri?: string;
    _showUri?: string;
    _storeUri?: string;
    _updateUri?: string;
    _destroyUri?: string;
    _multiDestroyUri?: string;
}
