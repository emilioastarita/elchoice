import {Pipe, PipeTransform} from "@angular/core";
import {User} from "../models/User";

@Pipe({
    name: 'filteredUsers',
    pure: false
})
export class FilterUsersPipe implements PipeTransform {
    transform(all:User[], filter:string) {
        if (!filter) {
            return all;
        }
        return all.filter(
            (user) => {
                let username = user.username.toLocaleLowerCase();
                let filterLower = filter.toLocaleLowerCase();
                return username.indexOf(filterLower) > -1;
            });

    }
}
