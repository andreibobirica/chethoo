let message = 'hello mondo cia';
console.log(message);
const title = 'Ciao';
//boolean number string
let isb = true;
let name = 'Andrei';
let sentence = `My name is ${name}, i am a beginner`;
let var2 = null;
let var3 = undefined;
isb = var2;
let list1 = [1, 3, 5, 7];
let list2 = [1, 2, 56, 7];
let person1 = ['Cmamma', 22];
var Color;
(function (Color) {
    Color[Color["Red"] = 0] = "Red";
    Color[Color["G"] = 1] = "G";
    Color[Color["B"] = 2] = "B";
})(Color || (Color = {}));
;
let c = Color.G;
console.log(c);
let tipoQual1 = "ciao";
tipoQual1 = true;
//tipoQual1();
let tipoQual2 = "variabile qualunque";
console.log(tipoQual2);
tipoQual2.toLocaleLowerCase();
//tipoQual2.toLocaleLowerCase();
function hasName(obj) {
    return !!obj &&
        typeof obj === "object" &&
        "name" in obj;
}
if (hasName(tipoQual2))
    console.log(tipoQual2.name);
let a;
a = 12;
a = true;
let b = 20;
b = true;
let mulTy;
mulTy = true;
mulTy = 10;
let anyT;
anyT = 10;
anyT = true;
function ifaddzero(num1, num2) {
    return (num1 + num2) == 0;
}
ifaddzero(8, -8);
ifaddzero(7);
function ifadduno(num1 = 8, num2 = 10) {
    return (num1 + num2) == 1;
}
ifadduno(8, -8);
ifadduno(7, 9);
function fullname(person) {
    console.log(`${person.firstname} ${person.lastname}`);
}
let p = {
    firstname: 'bruce',
    lastname: 'wayne'
};
fullname(p);
function fullname2(person) {
    console.log(`${person.firstname} ${person.lastname}`);
}
let bob = {
    firstname: 'bruce'
};
fullname2(bob);
class Empleyee {
    constructor(name) {
        this.emp = name;
    }
    getEmp() {
        return this.emp;
    }
}
let emp1 = new Empleyee('mARCO');
//console.log(emp1.emp);
console.log(emp1.getEmp());
class Manager extends Empleyee {
    constructor(managerName) {
        super(managerName);
        this.job = "nothing";
    }
    getJob() {
        return this.job;
    }
}
let m1 = new Manager('Matteo');
m1.getEmp();
m1.getJob();
console.log(m1);
export {};
