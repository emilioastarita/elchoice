import {Injectable} from "@angular/core";
import "rxjs/add/operator/toPromise";
import {Subject, BehaviorSubject} from "rxjs/Rx";
import {Router} from "@angular/router";

export interface IMessage {
    message:string;
    title:string;
    code: number;
    type:'error'|'message'|'flash'|'ignore';
}


@Injectable()
export class ErrorService {

    static initMessage : IMessage = {
        message: '',
        title: '',
        code: 0,
        type: 'ignore'
    };
    private announceSource = new BehaviorSubject<IMessage>(ErrorService.initMessage);
    public errorAnnounced = this.announceSource.asObservable();

    constructor(private router:Router) {

    }


    announceError(message:string, code = 500) {
        console.log('Announced error', message, code);
        this.announceSource.next({title: 'Oops', message: message, type: 'error', code: code});
        if (code === 401) {
            this.router.navigate(['members'])
        }
    }

    announceFlash(message:string, code: number, title = 'Message') {
        console.log('Announced flash', message);
        this.announceSource.next({title: title, message: message, type: 'flash', code: code});
    }

    promiseHandler() {
        return (error:any) => {
            console.warn('An error occurred', error);
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
            this.announceError(error.message || error, error.code || 500);
            return Promise.reject(error.message || error);
        }
    }

}



