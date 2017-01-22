import {Component, OnInit} from "@angular/core";
import {Router} from "@angular/router-deprecated";
import {ExamService} from "./exam.service";
import {Exam} from "../models/Exam";
import {ExamEditComponent} from "./exam-edit.component";
import {ErrorService} from "../error.service";
import {UserService} from "../users/user.service";
import {FilterExamsPipe} from "./filtered-exams";


@Component({
    selector: 'my-exams',
    templateUrl: 'app/exams/exams.component.html',
    providers: [ExamService],
    directives: <any[]>[ExamEditComponent],
    pipes: [FilterExamsPipe]
})
export class ExamsComponent implements OnInit {
    exams:Exam[] = [];
    userRole = 'user';
    filter = '';

    constructor(private router:Router,
                private errorService:ErrorService,
                private userService:UserService,
                private examService:ExamService) {
        userService.loginAnnounced.subscribe((role) => {
            this.userRole = role;
        });
        this.userRole = userService.userRole;

    }

    ngOnInit() {
        this.examService.getExams().then(exams => {
            this.exams = exams;
        });
    }


    addExam() {
        this.router.navigate(['ExamEditNew']);
    }

    edit(exam:Exam) {
        let link = ['ExamEdit', {id: exam.id}];
        console.log('exmas edit', link)
        this.router.navigate(link);
    }

    resolve(exam:Exam) {
        let link = ['ExamResolve', {id: exam.id}];
        this.router.navigate(link);
    }



    delete(user:Exam, event:any) {
        event.stopPropagation();
        if (!confirm('Confirm exam deletion?')) {
            return;
        }
        this.examService
            .delete(user)
            .then(res => {
                this.errorService.announceFlash('Deleted!', 0);
                this.exams = this.exams.filter(h => h !== user);
            })
            .catch(error => this.errorService.announceError(error));
    }

}