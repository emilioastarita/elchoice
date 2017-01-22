import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {User} from "../models/User";
import {UserService} from "./user.service";
import {RouteParams} from "@angular/router-deprecated";
import {ErrorService} from "../error.service";


@Component({
    selector: 'my-user-detail',
    templateUrl: './app/users/user-detail.component.html',

})
export class UserDetailComponent implements OnInit {
    @Input()
    user:User;

    availableRoles = ['user', 'manager', 'admin'];

    constructor(private userService:UserService,
                private errorService:ErrorService,
                private routeParams:RouteParams) {

    }

    ngOnInit() {
        if (this.routeParams.get('id') !== null) {
            const id = +this.routeParams.get('id');
            this.userService.getUser(id)
                .then(user => this.user = user);
        } else {
            this.user = new User();
            this.user.role = 'user';
        }
    }

    goBack(savedUser:User = null) {
        window.history.back();
    }

    save() {
        this.userService
            .save(this.user)
            .then(user => {
                this.errorService.announceFlash('Saved!', 0);
                this.user = user;
                this.goBack(user);
            });
    }


}
