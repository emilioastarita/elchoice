import {Component} from "@angular/core";
import {User} from "./models/User";
import {UserService} from "./users/user.service";
import {Router} from "@angular/router";
import {ErrorService} from "./error.service";


@Component({
    selector: 'my-members',
    styles: [`
            .mono {
                font-family: monospace;
            }
    `],
    template: `
        <div class="section white">
            <h4>Welcome {{loginUsername||registerUsername|| 'Guest'}}!</h4>
            <p class="flow-text">
                Hello <strong>{{loginUsername||registerUsername|| 'Guest'}}</strong>, 
                this is a choice trainer to try <a href="https://angular.io/">Angular 2</a>. 
            </p>
            
            <div class="row">
                <form class="login col s6" (ngSubmit)="onLogin()">
                    <h5 class="title">Login</h5>
                    <div class="input-field col s12">
                        <input name="username" [(ngModel)]="loginUsername" type="text" class="validate" id="username"  placeholder="Username" />
                        <label for="username" class="active">Username</label>
                    </div>
                    <div class="input-field col s12">
                        <input name="password" [(ngModel)]="loginPassword" type="password"  placeholder="Password" />
                        <label class="active">Password</label>
                    </div>
                    <button type="submit" class="btn" >Login</button>
                </form>
                <form class="register col s6" (ngSubmit)="onRegister()">
                    <h5 class="title">Register</h5>
                    <div class="input-field col s12">
                        <input name="username" type="text" [(ngModel)]="registerUsername" class="validate" id="username"  placeholder="Username" />
                        <label for="username"  class="active">Username</label>
                    </div>
                    <div class="input-field col s12">
                        <input name="password" type="password"  [(ngModel)]="registerPassword" placeholder="Password" />
                        <label class="active">Password</label>
                    </div>
                    <button type="submit" class="btn red lighten-1" >Register</button>                
                </form>
            </div>
            <div class="info light-blue lighten-5 center">
                <button class="btn teal lighten-5 black-text" (click)="showMore = !showMore" *ngIf="!showMore">More Info...</button>
                <div *ngIf="showMore" class="showMore">
                    <p>This demo was coded using: Typescript, Angular 2, Slim 3 and phpunit.</p>
                    <p>
                        Here you can do a login or register a member. The register only allows users of the role <em>user</em>.
                        If you want to try use <em>manager</em> or an <em>admin</em> role. 
                        You can login with one of the pre populated accounts: 
                    </p>
                    <ul class="row ">
                        <li class="col s4 mono">
                            <strong>username</strong>: admin<br />
                            <strong>password</strong>: admin<br /> 
                            <strong>role</strong>: admin
                        </li>
                        <li class="col s4 mono">
                            <strong>username</strong>: manager<br /> 
                            <strong>password</strong>: manager<br /> 
                            <strong>role</strong>: manager
                        </li>
                    </ul>
                    <button class="btn teal lighten-5  black-text" (click)="showMore = !showMore" >Got it!</button>
                </div>
            </div>            
        
        </div>
    
    `,

})
export class MembersComponent {
    user:User[] = [];

    showMore = false;
    private loginUsername = '';
    private loginPassword = '';

    private registerUsername = '';
    private registerPassword = '';

    constructor(private router:Router,
                private errorService:ErrorService,
                private userService:UserService) {
    }


    onLogin(e) {
        try {
            this.userService.login(this.loginUsername, this.loginPassword)
                .then((role) => {
                    if (role === 'user') {
                        console.log('navigate to exams user role');
                        this.router.navigate(['exams'])
                    } else if (role === 'admin') {
                        console.log('navigate to exams admin role');
                        this.router.navigate(['exams'])
                    }
                })
                .catch(error => this.errorService.announceError(error));
        } catch(e) {
            console.log('error submit ', e);
        }
    }

    onRegister(e) {
        try {
            console.log(this.registerUsername, this.registerPassword);
            this.userService.register(this.registerUsername, this.registerPassword)
                .then((result) => {
                    this.registerPassword = '';
                    this.loginUsername = this.registerUsername;
                    this.registerUsername = '';
                    //this.router.navigate(['exams'])
                })
                .catch(error => this.errorService.announceError(error));
        } catch(e) {
            console.log('error submit ', e);
        }
    }



}