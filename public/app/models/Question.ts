import {Answer} from "./Answer";
export class Question {
    id: number;
    examId: number;
    text: string = '';
    answers: Answer[];
    created: Date;
    updated: Date;
    allCorrect = false;
    userCheckAnswer = false;
    userShowAnswer  = false;

    constructor(json?: any) {
        this.answers = [];
        if (json) {
            this.load(json);
        } else {
            this.answers.push(new Answer());
        }
    }


    load(json: any) {
        ['id', 'examId', 'username',
            'text', 'created', 'updated'].forEach((idx => {
            this[idx] = json[idx]
        }));
        json.answers.forEach((jsonAnswer) => {
            this.answers.push(new Answer(jsonAnswer))
        });
    }

    checkAnswers() {
        this.userShowAnswer = false;
        let allCorrect = true;
        this.answers.forEach((answer) => {
            if (!answer.isCorrect()) {
                allCorrect = false;
            }
        });
        this.userCheckAnswer = true;
        this.allCorrect = allCorrect;
        console.log('allCorrect: ' , this.allCorrect, ' userCheckAnswer' , this.userCheckAnswer)
    }

}
