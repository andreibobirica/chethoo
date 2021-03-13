"use strict";
var __extends = (this && this.__extends) || (function () {
    var extendStatics = function (d, b) {
        extendStatics = Object.setPrototypeOf ||
            ({ __proto__: [] } instanceof Array && function (d, b) { d.__proto__ = b; }) ||
            function (d, b) { for (var p in b) if (Object.prototype.hasOwnProperty.call(b, p)) d[p] = b[p]; };
        return extendStatics(d, b);
    };
    return function (d, b) {
        if (typeof b !== "function" && b !== null)
            throw new TypeError("Class extends value " + String(b) + " is not a constructor or null");
        extendStatics(d, b);
        function __() { this.constructor = d; }
        d.prototype = b === null ? Object.create(b) : (__.prototype = b.prototype, new __());
    };
})();
exports.__esModule = true;
var message = 'hello mondo cia';
console.log(message);
var title = 'Ciao';
//boolean number string
var isb = true;
var name = 'Andrei';
var sentence = "My name is " + name + ", i am a beginner";
var var2 = null;
var var3 = undefined;
isb = var2;
var list1 = [1, 3, 5, 7];
var list2 = [1, 2, 56, 7];
var person1 = ['Cmamma', 22];
var Color;
(function (Color) {
    Color[Color["Red"] = 0] = "Red";
    Color[Color["G"] = 1] = "G";
    Color[Color["B"] = 2] = "B";
})(Color || (Color = {}));
;
var c = Color.G;
console.log(c);
var tipoQual1 = "ciao";
tipoQual1 = true;
//tipoQual1();
var tipoQual2 = "variabile qualunque";
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
var a;
a = 12;
a = true;
var b = 20;
b = true;
var mulTy;
mulTy = true;
mulTy = 10;
var anyT;
anyT = 10;
anyT = true;
function ifaddzero(num1, num2) {
    return (num1 + num2) == 0;
}
ifaddzero(8, -8);
ifaddzero(7);
function ifadduno(num1, num2) {
    if (num1 === void 0) { num1 = 8; }
    if (num2 === void 0) { num2 = 10; }
    return (num1 + num2) == 1;
}
ifadduno(8, -8);
ifadduno(7, 9);
function fullname(person) {
    console.log(person.firstname + " " + person.lastname);
}
var p = {
    firstname: 'bruce',
    lastname: 'wayne'
};
fullname(p);
function fullname2(person) {
    console.log(person.firstname + " " + person.lastname);
}
var bob = {
    firstname: 'bruce'
};
fullname2(bob);
var Empleyee = /** @class */ (function () {
    function Empleyee(name) {
        this.emp = name;
    }
    Empleyee.prototype.getEmp = function () {
        return this.emp;
    };
    return Empleyee;
}());
var emp1 = new Empleyee('mARCO');
//console.log(emp1.emp);
console.log(emp1.getEmp());
var Manager = /** @class */ (function (_super) {
    __extends(Manager, _super);
    function Manager(managerName) {
        var _this = _super.call(this, managerName) || this;
        _this.job = "nothing";
        return _this;
    }
    Manager.prototype.getJob = function () {
        return this.job;
    };
    return Manager;
}(Empleyee));
var m1 = new Manager('Matteo');
m1.getEmp();
m1.getJob();
console.log(m1);
