<?php

//Типизированные константы классов

//было
interface I {
    //we may naively assume that the PHP constant is always a string.
    const PHP = '8.2';
}

class Foo implements I {
    // But implementing classes may define it as an array.
    const PHP = [];
}

//стало
interface I {
    const string PHP = 'PHP 8.3';
}

class Foo implements I {
    const string PHP = [];
}
// Fatal error: Cannot use array as value for class constant
// Foo::PHP of type string


//Динамическое получение констант класса

//было
class Foo {
    const PHP = 'PHP 8.2';
}

$searchableConstant = 'PHP';

var_dump(constant(FOO::class . "::{$searchableConstant}"));

//стало
class Foo {
    const PHP = 'PHP 8.3';
}

$searchableConstant = 'PHP';

var_dump(Foo::{$searchableConstant});
