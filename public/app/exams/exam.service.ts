import {Injectable} from "@angular/core";
import {Headers, Http} from "@angular/http";
import "rxjs/add/operator/toPromise";

import {ErrorService} from "../error.service";
import {Exam} from "../models/Exam";

@Injectable()
export class ExamService {
    private examsUrl = '/api/exams';

    constructor(private http:Http,
                private errorService:ErrorService) {

    }

    getExams():Promise<Exam[]> {
        return this.http.get(`${this.examsUrl}/`)
            .toPromise()
            .then(response => response.json()
                .map((json) => new Exam(json)))
            .catch(this.errorService.promiseHandler())
    }

    getExam(id:number):Promise<Exam> {
        return this.http.get(`${this.examsUrl}/${id}`).toPromise()
            .then(response => new Exam(response.json()))
            .catch(this.errorService.promiseHandler());
    }

    save(exam:Exam):Promise<Exam> {
        if (exam.id) {
            return this.put(exam);
        }
        return this.post(exam);
    }


    delete(exam:Exam) {
        let headers = new Headers();
        headers.append('Content-Type', 'application/json');

        let url = `${this.examsUrl}/${exam.id}`;

        return this.http
            .delete(url, headers)
            .toPromise()
            .catch(this.errorService.promiseHandler());
    }

    private post(exam:Exam):Promise<Exam> {
        let headers = new Headers({
            'Content-Type': 'application/json'
        });

        return this.http
            .post(`${this.examsUrl}/`, JSON.stringify(exam), {headers: headers})
            .toPromise()
            .then(res => res.json())
            .catch(this.errorService.promiseHandler());
    }

    private put(exam:Exam):Promise<Exam> {
        let headers = new Headers();
        headers.append('Content-Type', 'application/json');

        let url = `${this.examsUrl}/${exam.id}`;

        return this.http
            .put(url, JSON.stringify(exam), {headers: headers})
            .toPromise()
            .then(() => exam)
            .catch(this.errorService.promiseHandler());
    }



}
