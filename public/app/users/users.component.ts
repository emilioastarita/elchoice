import {Component, OnInit} from "@angular/core";
import {UserDetailComponent} from "./user-detail.component";
import {User} from "../models/User";
import {UserService} from "./user.service";
import {Router} from "@angular/router";
import {ErrorService} from "../error.service";
import {FilterUsersPipe} from "./filtered-users";


@Component({
    selector: 'my-users',
    templateUrl: './app/users/users.component.html',
    styleUrls:  ['out/users.component.css'],
})

export class UsersComponent implements OnInit {
    public users:User[];
    error: any;
    filter = '';

    constructor(private router: Router,
                private errorService:ErrorService,
                private userService:UserService) {
    }

    getUsers() {
        this.userService.getUsers().then((users) => {
            this.users = users
        });
    }

    ngOnInit() {
        this.getUsers();
    }

    details(user: User, event: any) {
        event.stopPropagation();
        this.router.navigate(['users', user.id]);
    }

    addUser() {
        event.stopPropagation();
        this.router.navigate(['users/new']);
    }

    delete(user: User, event: any) {
        event.stopPropagation();
        if (!confirm('Confirm deletion of user and all her/his associated questions?')) {
            return;
        }
        this.userService
            .delete(user)
            .then(res => {
                this.errorService.announceFlash('Deleted!', 0);
                this.users = this.users.filter(h => h !== user);
            })
            .catch(error => this.error = error);
    }


}

