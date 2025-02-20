export default interface IDataView {
    uuid: string;
    user_account_id: string;
    model: string,
    columns: Array<string>,
    visibility_guard: string,
    default: Array<string>
}
