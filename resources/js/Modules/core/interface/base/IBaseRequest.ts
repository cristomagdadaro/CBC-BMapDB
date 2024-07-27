export default interface IBaseRequest {
    page: number;
    per_page: number;
    sort: string;
    order: string;

    search?: string;
    filter?: string;
    is_exact?: boolean;
}
