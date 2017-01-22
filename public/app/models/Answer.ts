

export class Answer {
    id: number;
    questionId: number;
    text: string = '';
    correct: boolean = false;
    userAnswer: boolean = false;
    userShowStatus : boolean = false;
    created: Date;
    updated: Date;

    constructor(json?: any) {
        if (json) this.load(json);
    }

    public load(json: any) {
        ['id', 'questionId', 'correct', 'text', 'created', 'updated'].forEach((idx => {
            this[idx] = json[idx]
        }));
    }

    getHtmlId()
    {
        return 'answer_' + this.questionId + '_' + this.id;
    }

    checkAnswer()
    {
        this.userShowStatus = true;
    }

    isCorrect() {
        return this.userAnswer === this.correct;
    }
}
