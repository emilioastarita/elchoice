import {Pipe, PipeTransform} from "@angular/core";
import {Question} from "../models/Question";

@Pipe({
    name: 'filteredQuestions',
    pure: false
})
export class FilterQuestionsPipe implements PipeTransform {
    transform(all:Question[], filter:string) {
        if (!filter) {
            return all;
        }
        return all.filter(
            (question) => {
                let text = question.text.toLocaleLowerCase();
                let filterLower = filter.toLocaleLowerCase();

                // let startDate = question.startDate;
                return text.indexOf(filterLower) > -1;
            });

    }
}
