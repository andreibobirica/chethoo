export {}
let message = 'hello mondo cia';
console.log(message);

const title = 'Ciao';
//boolean number string
let isb : boolean = true;
let name : string = 'Andrei';
let sentence : string = `My name is ${name}, i am a beginner`;
let var2 : null = null;
let var3 : undefined = undefined;
isb = var2;

let list1 : number[] = [1,3,5,7];
let list2: Array<number> = [1,2,56,7];
let person1: [string,number] = ['Cmamma',22];
enum Color {Red, G,B};
let c : Color = Color.G;
console.log(c);

let tipoQual1 : any = "ciao";
tipoQual1 = true;
//tipoQual1();

let tipoQual2 : unknown = "variabile qualunque";
console.log(tipoQual2);
(tipoQual2 as string).toLocaleLowerCase();
//tipoQual2.toLocaleLowerCase();

function hasName(obj: any): obj is {name : string}{
    return !!obj &&
        typeof obj === "object" &&
        "name" in obj
}

if(hasName(tipoQual2))
    console.log(tipoQual2.name);




let a;
a = 12;
a = true;
let b : unknown = 20;
b = true;

let mulTy : number | boolean;
mulTy = true;
mulTy = 10;


let anyT : any;
anyT = 10;
anyT = true;


function ifaddzero(num1 : number , num2? : number):boolean{
    return (num1 + num2)==0;
}

ifaddzero(8,-8);
ifaddzero(7);

function ifadduno(num1 : number =8, num2 : number = 10):boolean{
    return (num1 + num2)==1;
}

ifadduno(8,-8);
ifadduno(7,9);


function fullname(person : {firstname: string, lastname:string}){
    console.log(`${person.firstname} ${person.lastname}`);
}
let p = {
    firstname: 'bruce',
    lastname: 'wayne'
}
fullname(p);


interface Person {
    firstname: string,
    lastname?: string //optional
}
function fullname2(person : Person){
    console.log(`${person.firstname} ${person.lastname}`);
}
let bob : Person = {
    firstname: 'bruce'
}
fullname2(bob);

class Empleyee {
    protected emp : string;

    public constructor(name : string){
        this.emp = name;
    }

    getEmp(){
        return this.emp;
    }
}
let emp1 = new Empleyee('mARCO');
//console.log(emp1.emp);
console.log(emp1.getEmp());



class Manager extends Empleyee {
    job : string;
    constructor(managerName:string){
        super(managerName);
        this.job = "nothing";
    }
    getJob(){
        return this.job;
    }
}
let m1 = new Manager('Matteo');
m1.getEmp();
m1.getJob();
console.log(m1)
