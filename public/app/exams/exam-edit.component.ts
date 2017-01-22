import {Component, OnInit} from "@angular/core";
import {Exam} from "../models/Exam";
import {ExamService} from "./exam.service";
import {RouteParams} from "@angular/router-deprecated";
import {ErrorService} from "../error.service";
import {Question} from "../models/Question";
import {Router} from "@angular/router-deprecated";
import {QuestionService} from "../questions/question.service";
import {FilterQuestionsPipe} from "../questions/filtered-questions";

@Component({
    selector: 'exam-edit',
    templateUrl: './app/exams/exam-edit.component.html',
    providers: [ExamService],
    pipes: [FilterQuestionsPipe]
})
export class ExamEditComponent implements OnInit {

    exam:Exam;

    constructor(private router:Router,
                private examService:ExamService,
                private questionService:QuestionService,
                private errorService:ErrorService,
                private routeParams:RouteParams) {

    }

    ngOnInit() {
        if (this.routeParams.get('id') !== null) {
            const id = +this.routeParams.get('id');
            this.examService.getExam(id)
                .then(exam => this.exam = exam);
        } else {
            this.exam = new Exam();
        }
    }


    cancel() {
        this.router.navigate(['Exams']);
    }

    save() {
        this.examService
            .save(this.exam)
            .then((exam) => {
                this.errorService.announceFlash('Saved!', 0);
                let link = ['ExamEdit', {id: exam.id}];
                this.router.navigate(link);
            })
            .catch(error => this.errorService.announceError(error));

    }

    addQuestion() {
        event.stopPropagation();
        this.router.navigate(['QuestionEditNew', { examId: this.exam.id}]);
    }

    editQuestion(question:Question) {
        let link = ['QuestionEdit', {id: question.id, examId: this.exam.id}];
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
