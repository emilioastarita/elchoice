import {Component, OnInit} from "@angular/core";

import {RouteConfig, ROUTER_DIRECTIVES, ROUTER_PROVIDERS, RouteDefinition, Router} from "@angular/router-deprecated";
import {ExamsComponent} from "./exams/exams.component";
import {MembersComponent} from "./members.component";
import {QuestionEditComponent} from "./questions/question-edit.component";
import {QuestionService} from "./questions/question.service";

import {UsersComponent} from "./users/users.component";
import {UserService} from "./users/user.service";
import {UserDetailComponent} from "./users/user-detail.component";

import {ErrorService} from "./error.service";
import {MessageComponent} from "./message.component";
import {ExamService} from "./exams/exam.service";
import {ExamEditComponent} from "./exams/exam-edit.component";
import {ExamResolveComponent} from "./exams/exam-resolve.component";



declare var jQuery:any;

@Component({
    selector: 'my-app',
    template: `
  <nav class="no-print">
    <div class="nav-wrapper black">
      <a href="#" data-activates="mobile-navbar" class="button-collapse"><i class="material-icons">menu</i></a>
      <ul id="nav-mobile" class="left hide-on-med-and-down">
        <li [class.active]="router.isRouteActive(router.generate(['/Exams']))">
            <a [routerLink]="['Exams']" *ngIf="userRole === 'user' || userRole === 'admin' ">Exams</a>
        </li>       
        <li [class.active]="router.isRouteActive(router.generate(['/Users']))" >
            <a [routerLink]="['Users']"  *ngIf="userRole === 'admin' || userRole === 'manager'">Users</a>
        </li>
        <li [class.active]="router.isRouteActive(router.generate(['/Members']))">
            <a [routerLink]="['Members']" *ngIf="userRole === 'guest'">Members</a>
        </li>
        <li>
            <a (click)="logout()"  *ngIf="userRole !==  'guest'">Logout ({{userRole}})</a>
        </li>
      </ul>
      <ul id="mobile-navbar"  class="side-nav">
        <li><a [routerLink]="['Users']" *ngIf="userRole === 'admin' || userRole === 'manager'">Users</a></li>
        <li><a [routerLink]="['Exams']" *ngIf="userRole === 'user' || userRole === 'admin' ">Exams</a></li>
        <li><a [routerLink]="['Members']" *ngIf="userRole === 'guest'">Members</a></li>
        <li><a (click)="logout()"  *ngIf="userRole !==  'guest'">Logout ({{userRole}})</a></li>
      </ul>      
    </div>
  </nav>
    <div class="routerOutletContainer">
      <my-message></my-message>
      <router-outlet></router-outlet>
    </div>
`,
    directives: <any[]>[ROUTER_DIRECTIVES, MessageComponent],
    providers: [UserService, QuestionService, ExamService, ErrorService, ROUTER_PROVIDERS]
})
@RouteConfig(<RouteDefinition[]>[
    {
        path: '/members',
        name: 'Members',
        component: MembersComponent,
        useAsDefault: true
    },
    {
        path: '/exams',
        name: 'Exams',
        component: ExamsComponent,
    },
    {
        path: '/exams-edit/:id',
        name: 'ExamEdit',
        component: ExamEditComponent
    },
    {
        path: '/exams-edit/new',
        name: 'ExamEditNew',
        component: ExamEditComponent
    },
    {
        path: '/exams-resolve/:id',
        name: 'ExamResolve',
        component: ExamResolveComponent
    },
    {
        path: '/users',
        name: 'Users',
        component: UsersComponent,

    },
    {
        path: '/users/:id',
        name: 'UserDetail',
        component: UserDetailComponent
    },
    {
        path: '/users/new',
        name: 'UserDetailNew',
        component: UserDetailComponent
    },
    {
        path: '/exams-edit/:examId/questions/new',
        name: 'QuestionEditNew',
        component: QuestionEditComponent
    },
    {
        path: '/exams-edit/:examId/questions/:id',
        name: 'QuestionEdit',
        component: QuestionEditComponent
    },
])
export class AppComponent implements OnInit {
    title = 'El Choice Trainer';
    userRole = 'guest';

    constructor(private router:Router,
                private userService:UserService) {
        userService.loginAnnounced.subscribe((role) => {
            this.userRole = role;
        });

    }

    ngOnInit() {
        this.userService.checkLoginStatus();
        jQuery(".button-collapse").sideNav({closeOnClick: true});
    }


    logout() {
        if (!confirm('Do you want to logout?')) {
            return;
        }
        this.userService.logout().then(() => {
            this.router.navigate(['Members'])
        });
    }
}