import {NgModule} from "@angular/core";
import {BrowserModule} from "@angular/platform-browser";
import {FormsModule} from "@angular/forms";
import {RouterModule, Routes} from "@angular/router";
import {AppComponent} from "./app.component";
import {MessageComponent} from "./message.component";
import {HttpModule} from "@angular/http";
import {MembersComponent} from "./members.component";
import {ExamsComponent} from "./exams/exams.component";
import {ExamEditComponent} from "./exams/exam-edit.component";
import {ExamResolveComponent} from "./exams/exam-resolve.component";
import {UsersComponent} from "./users/users.component";
import {UserDetailComponent} from "./users/user-detail.component";
import {QuestionEditComponent} from "./questions/question-edit.component";
import {FilterUsersPipe} from "./users/filtered-users";
import {FilterExamsPipe} from "./exams/filtered-exams";
import {FilterQuestionsPipe} from "./questions/filtered-questions";
import { Ng2PageTransition } from "ng2-page-transition";
import {ErrorService} from "./error.service";
import {UserService} from "./users/user.service";
import {QuestionService} from "./questions/question.service";
import {ExamService} from "./exams/exam.service";


const appRoutes :Routes = [
    {path: '', redirectTo: 'exams', pathMatch: 'full'},

    {
        path: 'members',
        component: MembersComponent,
    },
    {
        path: 'exams',
        component: ExamsComponent,
    },
    {
        path: 'exams-edit/:id',
        component: ExamEditComponent
    },
    {
        path: 'exams-edit/new',
        component: ExamEditComponent
    },
    {
        path: 'exams-resolve/:id',
        component: ExamResolveComponent
    },
    {
        path: 'users',
        component: UsersComponent,

    },
    {
        path: 'users/:id',
        component: UserDetailComponent
    },
    {
        path: 'users/new',
        component: UserDetailComponent
    },
    {
        path: 'exams-edit/:examId/questions/new',
        component: QuestionEditComponent
    },
    {
        path: 'exams-edit/:examId/questions/:id',
        component: QuestionEditComponent
    },
];



@NgModule({
    imports:      [
        BrowserModule,
        HttpModule,
        FormsModule,
        RouterModule.forRoot(appRoutes)
    ],
    providers: [
        ErrorService,
        UserService,
        QuestionService,
        ExamService
    ],
    declarations: [

        AppComponent,
        MessageComponent,
        MembersComponent,
        QuestionEditComponent,
        ExamEditComponent,
        ExamsComponent,
        ExamResolveComponent,
        UserDetailComponent,
        UsersComponent,
        FilterUsersPipe,
        FilterExamsPipe,
        FilterQuestionsPipe,
        Ng2PageTransition,
    ],
    bootstrap:    [ AppComponent ]
})
export class AppModule { }
