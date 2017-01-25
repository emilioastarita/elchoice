import {Injectable} from "@angular/core";
import {Headers, Http} from "@angular/http";
import "rxjs/add/operator/toPromise";
import {User} from "../models/User";
import {Subject} from "rxjs/Rx";
import {ErrorService} from "../error.service";


@Injectable()
export class UserService {
    private usersUrl = '/api/users';
    public userRole = 'guest';


    private loginAnnounceSource = new Subject<string>();
    public loginAnnounced = this.loginAnnounceSource.asObservable();

    constructor(private http: Http,
                private errorService: ErrorService) {

    }

    announceLogin(role: string) {
        this.loginAnnounceSource.next(role);
        this.userRole = role;
    }

    checkLoginStatus() {
        console.log('checkLoginStatus')
        this.http.get('/api/members/login-status').toPromise()
            .then((res) => {
                this.announceLogin(res.json().role);
            }).catch((error) => {

            this.announceLogin('guest');
        })
    }

    login(username, password) {
        let headers = new Headers();
        headers.append('Content-Type', 'application/json');
        return this.http
            .post(
                '/api/members/login',
                JSON.stringify({username, password}),
                {headers}
            ).toPromise()
            .then((res) => {
                let role = res.json().role;
                this.errorService.announceFlash('Credentials verified!', 0);
                this.announceLogin(role);
                return role;
            }).catch((error) => {
                this.announceLogin('guest');
                return this.errorService.promiseHandler()(error);
            });
    }

    logout() {
        this.announceLogin('guest');
        return this.http
            .get('/api/members/logout').toPromise();

    }

    register(username, password) {
        let headers = new Headers();
        headers.append('Content-Type', 'application/json');
        return this.http
            .post(
                '/api/members/register',
                JSON.stringify({username, password}),
                {headers}
            ).toPromise()
            .then((res) => {
                let role = res.json().role;
                this.errorService.announceFlash('New user (' + role + ') created! Now you can login', 0);
            }).catch((error) => {
                this.announceLogin('guest');
                return this.errorService.promiseHandler()(error);
            });
    }


    getUsers(): Promise<User[]> {
        return this.http.get(`${this.usersUrl}/`)
            .toPromise()
            .then(response => response.json())
            .catch(this.errorService.promiseHandler())
    }

    getUser(id: number): Promise<User> {
        return this.getUsers()
            .then(users => users.find(u => u.id === id))
            .catch(this.errorService.promiseHandler());
    }

    save(user: User): Promise<User> {
        if (user.id) {
            return this.put(user);
        }
        return this.post(user);
    }

    private post(user: User): Promise<User> {
        let headers = new Headers({
            'Content-Type': 'application/json'
        });

        return this.http
            .post(`${this.usersUrl}/`, JSON.stringify(user), {headers: headers})
            .toPromise()
            .then(res => res.json().data)
            .catch(this.errorService.promiseHandler());
    }

    private put(user: User) {
        let headers = new Headers();
        headers.append('Content-Type', 'application/json');
        let url = `${this.usersUrl}/${user.id}`;
        return this.http
            .put(url, JSON.stringify(user), {headers: headers})
            .toPromise()
            .then(() => user)
            .catch(this.errorService.promiseHandler());
    }

    delete(user: User) {
        let headers = new Headers();
        headers.append('Content-Type', 'application/json');

        let url = `${this.usersUrl}/${user.id}`;

        return this.http
            .delete(url, headers)
            .toPromise()
            .catch(this.errorService.promiseHandler());
    }


}
