import {Injectable} from "@angular/core";
import {Headers, Http} from "@angular/http";
import "rxjs/add/operator/toPromise";
import {Question} from "../models/Question";
import {ErrorService} from "../error.service";

@Injectable()
export class QuestionService {
    private questionsUrl = '/api/questions';

    constructor(private http:Http,
                private errorService:ErrorService) {

    }


    getQuestion(id:number):Promise<Question> {
        return this.http.get(`${this.questionsUrl}/${id}`).toPromise()
            .then(response => new Question(response.json()))
            .catch(this.errorService.promiseHandler());
    }

    getErrorPromise(question:Question) {
        console.log('Question is', question);
        if (!question.text.trim().length) {
            return Promise.reject('You need complete the question text.');
        }
        if (question.answers.length < 1) {
            return Promise.reject('You need at least one answer');
        }
        let thereIsCorrect = false;
        question.answers.forEach((answer) => {
            if (answer.correct) {
                thereIsCorrect = true;
            }
        });
        if (thereIsCorrect === false) {
            return Promise.reject('You need at least one correct answer');
        }
        return null;
    }

    save(question:Question):Promise<Question>|Promise<any> {
        const promise = this.getErrorPromise(question);
        if (promise) {
            return promise;
        }
        if (question.id) {
            return this.put(question);
        }
        return this.post(question);
    }


    delete(question:Question) {
        let headers = new Headers();
        headers.append('Content-Type', 'application/json');

        let url = `${this.questionsUrl}/${question.id}`;

        return this.http
            .delete(url, headers)
            .toPromise()
            .catch(this.errorService.promiseHandler());
    }

    private post(question:Question):Promise<Question> {
        let headers = new Headers({
            'Content-Type': 'application/json'
        });

        return this.http
            .post(`${this.questionsUrl}/`, JSON.stringify(question), {headers: headers})
            .toPromise()
            .then(res => res.json().data)
            .catch(this.errorService.promiseHandler());
    }

    private put(question:Question):Promise<Question> {
        let headers = new Headers();
        headers.append('Content-Type', 'application/json');

        let url = `${this.questionsUrl}/${question.id}`;

        return this.http
            .put(url, JSON.stringify(question), {headers: headers})
            .toPromise()
            .then(() => question)
            .catch(this.errorService.promiseHandler());
    }



}
