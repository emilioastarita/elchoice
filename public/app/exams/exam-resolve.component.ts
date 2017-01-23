import {Component, OnInit} from "@angular/core";
import {Exam} from "../models/Exam";
import {ExamService} from "./exam.service";
import {ActivatedRoute, Params} from "@angular/router";
import {ErrorService} from "../error.service";
import {QuestionEditComponent} from "../questions/question-edit.component";
import {FilterQuestionsPipe} from "../questions/filtered-questions";
import {Question} from "../models/Question";


@Component({
    selector: 'exam-resolve',
    templateUrl: './app/exams/exam-resolve.component.html',
    providers: [ExamService, ErrorService],
    // directives: [QuestionEditComponent],
    // pipes: [FilterQuestionsPipe]
})
export class ExamResolveComponent implements OnInit {

    exam:Exam;
    correct = 0;
    wrong = 0;
    notResolved = 0;
    total = 0;
    resolved = 0;

    constructor(private examService:ExamService,
                private errorService:ErrorService,
                private route:ActivatedRoute) {

    }

    ngOnInit() {
        this.route.params
            .switchMap((params: Params) => this.examService.getExam(+params['id']))
            .subscribe((exam) => {
                this.exam = exam;
                this.calcStats()
            });
    }

    showAnswer(question:Question) {
        question.userShowAnswer = true;
        this.calcStats();
    }

    checkAnswers(question:Question) {
        question.checkAnswers();
        this.calcStats();
    }

    protected calcStats() {
        this.notResolved = this.total = this.exam.questions.length;
        this.resolved = 0;
        this.wrong = 0;
        this.correct = 0;
        this.exam.questions.forEach((q)=>{
           if (q.userCheckAnswer) {
                this.resolved++;
                if (q.allCorrect) {
                    this.correct++;
                } else {
                    this.wrong++;
                }
           } else {
               this.notResolved--;
           }
        });
    }

    goBack() {
        window.history.back();
    }



}
