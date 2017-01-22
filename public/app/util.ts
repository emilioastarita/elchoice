

export function toDateString(date:Date):string {
    return (date.getFullYear().toString() + '-'
    + ("0" + (date.getMonth() + 1)).slice(-2) + '-'
    + ("0" + (date.getDate())).slice(-2));
};
