import {Pipe, PipeTransform} from "@angular/core";

import {Exam} from "../models/Exam";

@Pipe({
    name: 'filteredExams',
    pure: false
})
export class FilterExamsPipe implements PipeTransform {
    transform(all:Exam[], filter:string) {
        if (!filter) {
            return all;
        }
        return all.filter(
            (exam) => {
                let text = exam.name.toLocaleLowerCase();
                let filterLower = filter.toLocaleLowerCase();
                return text.indexOf(filterLower) > -1;
            });

    }
}
