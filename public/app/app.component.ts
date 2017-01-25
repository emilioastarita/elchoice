import {Component, OnInit} from "@angular/core";
import { RouterModule }   from '@angular/router';
import { Router }   from '@angular/router';


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
        <li routerLink="/exams" routerLinkActive="active">
            <a routerLink="/exams" *ngIf="userRole === 'user' || userRole === 'admin' ">Exams</a>
        </li>       
        <li routerLink="/users" routerLinkActive="active">
            <a routerLink="/users"  *ngIf="userRole === 'admin' || userRole === 'manager'">Users</a>
        </li>
        <li routerLink="/members" routerLinkActive="active">
            <a routerLink="/members" *ngIf="userRole === 'guest'">Members</a>
        </li>
        <li>
            <a (click)="logout()"  *ngIf="userRole !==  'guest'">Logout ({{userRole}})</a>
        </li>
      </ul>
      <ul id="mobile-navbar"  class="side-nav">
        <li><a routerLink="/users" *ngIf="userRole === 'admin' || userRole === 'manager'">Users</a></li>
        <li><a routerLink="/exams" *ngIf="userRole === 'user' || userRole === 'admin' ">Exams</a></li>
        <li><a routerLink="/members" *ngIf="userRole === 'guest'">Members</a></li>
        <li><a (click)="logout()"  *ngIf="userRole !==  'guest'">Logout ({{userRole}})</a></li>
      </ul>      
    </div>
  </nav>
    <div class="routerOutletContainer">
      <message></message>
      <ng2-page-transition>
          <router-outlet></router-outlet>
      </ng2-page-transition>
    </div>
`
})
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
            this.router.navigate(['members'])
        });
    }
}