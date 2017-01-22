import {Injectable} from "@angular/core";
import "rxjs/add/operator/toPromise";
import {Subject} from "rxjs/Rx";

export interface IMessage {
    message:string;
    title:string;
    code: number;
    type:'error'|'message'|'flash';
}


@Injectable()
export class ErrorService {

    private announceSource = new Subject<IMessage>();
    public errorAnnounced = this.announceSource.asObservable();

    announceError(message:string, code = 500) {
        console.log('Announced error', message);
        this.announceSource.next({title: 'Oops', message: message, type: 'error', code: code});
    }

    announceFlash(message:string, code: number, title = 'Message') {
        console.log('Announced flash', message);
        this.announceSource.next({title: title, message: message, type: 'flash', code: code});
    }

    promiseHandler() {
        var self = this;
        return (error:any) => {
            console.error('An error occurred', error);
            if (typeof(error._body) !== 'undefined') {
                try {
                    console.log(error._body);
                    let parsed = JSON.parse(error._body);
                    error.message = parsed.error;
                    error.code = 500;
                    if (typeof(parsed.code) !== 'undefined') {
                        error.code = parsed.code;
                    }

                } catch (e) {
                }
            }
            self.announceError(error.message || error, error.code || 500);
            return Promise.reject(error.message || error);
        }
    }

}



