import {Component, OnInit, Input} from "@angular/core";
import {Question} from "../models/Question";
import {QuestionService} from "./question.service";
import {ActivatedRoute, Params} from "@angular/router";
import {ErrorService} from "../error.service";
import {Answer} from "../models/Answer";


@Component({
    selector: 'question-edit',
    templateUrl: './app/questions/question-edit.component.html',

})
export class QuestionEditComponent implements OnInit {

    question : Question;

    constructor(private questionService:QuestionService,
                private errorService:ErrorService,
                private route:ActivatedRoute) {

    }

    ngOnInit() {

        const switchMap = (params: Params) => {
            console.log('params are', params);
            if (!params['id']) {
                const question = new Question();
                question.examId = parseInt(params['examId'], 10);
                return Promise.resolve(question);
            } else {
                return this.questionService.getQuestion(+params['id']);
            }
        };
        this.route.params
            .switchMap(switchMap)
            .subscribe((question:Question) => {
                this.question = question;
            });

    }

    private static toDateString(date):string {
        return (date.getFullYear().toString() + '-'
            + ("0" + (date.getMonth() + 1)).slice(-2) + '-'
        + ("0" + (date.getDate())).slice(-2));
    }


    goBack() {
        window.history.back();
    }

    save() {
        this.questionService
            .save(this.question)
            .then(question => {
                this.question = question;
                this.errorService.announceFlash('Saved!', 0);
                this.goBack();
            })
            .catch(error => this.errorService.announceError(error));
    }

    addAnswer() {
        const a = new Answer();
        this.question.answers.push(a);
    }

    removeAnswer(answer:Answer) {
        if (!confirm('Do you want to remove this answer?')) {
            return;
        }
        this.question.answers = this.question.answers.filter(h => h !== answer);
    }


}
