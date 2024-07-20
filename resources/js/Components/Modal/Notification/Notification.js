import {BaseClass} from "@/Modules/core/domain/BaseClass.js";
import {ref} from "vue";

export default class Notification extends BaseClass {
    static notifications = ref([]);

    constructor(id = 0,
                title = '',
                message = '',
                type = 'failed',
                timeout = 5000,
                show= true) {
        super();
        this.id = Notification.notifications.value.length + 1;
        this.title = title;
        this.message = message;
        this.type = type;
        this.timeout = timeout;
        this.show = show;

        if (this.show) {
            Notification.notifications.value.push(this);
        }
    }

    static pushNotification( param = {
        id: 0,
        title: '',
        message: '',
        type: 'failed',
        timeout: 5000,
        show: true
    }){
        new this.prototype.constructor(
            param.id,
            param.title,
            param.message,
            param.type,
            param.timeout,
            param.show,
        );

        if (!param.timeout)
            return;

        setTimeout(() => {
            this.notifications.value.shift();
        }, param.timeout);
    }

    close(){
        Notification.notifications.value.splice(Notification.notifications.value.indexOf(this), 1);
    }
}
