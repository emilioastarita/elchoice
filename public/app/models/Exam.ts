import {Question} from "./Question";
import {toDateString} from "../util";
export class Exam {
    id: number;
    userId: number;
    username: string;
    name: string;
    questions: Question[];
    published: Date;
    created: Date;
    updated: Date;

    constructor(json?: any) {
        if (json) this.load(json);
        if (!this.published) {
            this.published = new Date();
        }
    }


    load(json: any) {
        ['id', 'userId', 'name',
            'published', 'created', 'updated'].forEach((idx => {
            this[idx] = json[idx];
            if (idx === 'published') {
                this[idx] = new Date(json[idx]);
            }
        }));
        this.questions = [];
        if (!json.questions) {
            return;
        }
        json.questions.forEach((jsonQuestion) => {
            this.questions.push(new Question(jsonQuestion))
        });
    }

    set humanPublished(e){
        const t = e.split('-').map(function(num) { return parseInt(num); });
        let d = new Date(Date.UTC(t[0], t[1]-1, t[2]));
        this.published.setFullYear(d.getUTCFullYear(), d.getUTCMonth(), d.getUTCDate());
    }

    get humanPublished(){
        return toDateString(this.published);
    }

}
