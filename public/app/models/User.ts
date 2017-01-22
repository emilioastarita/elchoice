export class User {
    id:number;
    username:string;
    password:string;
    created: Date;
    updated: Date;
    role: "user" | "manager" | "admin";
}
