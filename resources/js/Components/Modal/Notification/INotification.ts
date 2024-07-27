export default interface INotification {
    id: number;
    title: string;
    message: string;
    type: string;
    timeout: number;
    show: boolean;
}
