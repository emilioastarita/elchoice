import {Component, EventEmitter, Input, OnInit, Output} from '@angular/core';
import {User} from "../models/User";
import {UserService} from "./user.service";
import {ActivatedRoute, Params} from "@angular/router";
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
                private route:ActivatedRoute) {

    }

    ngOnInit() {
        const switchMap = (params: Params) => {
            if (params['id'] === 'new') {
                const user = new User();
                user.role = 'user';
                return Promise.resolve(user);
            } else {
                return this.userService.getUser(+params['id']);
            }
        };
        this.route.params
            .switchMap(switchMap)
            .subscribe((user:User) => {
                this.user = user;
            });
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
