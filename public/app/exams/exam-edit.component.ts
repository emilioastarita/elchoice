import {Component, OnInit} from "@angular/core";
import {Exam} from "../models/Exam";
import {ExamService} from "./exam.service";

import {ErrorService} from "../error.service";
import {Question} from "../models/Question";
import {Router, ActivatedRoute, Params} from "@angular/router";
import {QuestionService} from "../questions/question.service";
import {FilterQuestionsPipe} from "../questions/filtered-questions";
import {Observable} from "rxjs";

@Component({
    selector: 'exam-edit',
    templateUrl: './app/exams/exam-edit.component.html',
    providers: [ExamService],
})
export class ExamEditComponent implements OnInit {

    exam:Exam;

    constructor(private router:Router,
                private route:ActivatedRoute,
                private examService:ExamService,
                private questionService:QuestionService,
                private errorService:ErrorService
                ) {

    }

    ngOnInit() {

        const switchMap = (params: Params) => {
            if (params['id'] === 'new') {
                return Promise.resolve(new Exam());
            } else {
                return this.examService.getExam(+params['id']);
            }
        };

        this.route.params
            .switchMap(switchMap)
            .subscribe((exam:Exam) => {
                this.exam = exam;
            });

    }


    cancel() {
        this.router.navigate(['exams']);
    }

    save() {
        this.examService
            .save(this.exam)
            .then((exam) => {
                this.errorService.announceFlash('Saved!', 0);
                let link = ['exams-edit', exam.id];
                this.router.navigate(link);
            })
            .catch(error => this.errorService.announceError(error));

    }

    addQuestion() {
        event.stopPropagation();
        this.router.navigate(['exams-edit', this.exam.id, 'questions', 'new']);
    }

    editQuestion(question:Question) {
        let link = ['exams-edit', this.exam.id, 'questions', question.id];
        this.router.navigate(link);
    }


    deleteQuestion(question:Question) {
        if (!confirm('Do you want to delete this question?')) {
            return;
        }
        this.questionService
            .delete(question)
            .then(res => {
                this.errorService.announceFlash('Question deleted!', 0);
                this.exam.questions = this.exam.questions.filter(h => h !== question);
            })
            .catch(error => this.errorService.announceError(error));
    }


}
