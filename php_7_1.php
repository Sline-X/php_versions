<?php

//обнуляемые типы
//указанные параметры и возвращаемые значения, могут быть как указанного типа, так и null.

function testReturn(): ?string
{
    return 'elePHPant';
}

var_dump(testReturn());

function testReturn2(): ?string
{
    return null;
}

var_dump(testReturn2());

function test(?string $name)
{
    var_dump($name);
}

test('elePHPant');
test(null);
test();

//Ничего не возвращающие функции
//не содержать ни одного оператора return, либо использовать его без параметра. 
//null не является корректным значением для возврата в таких функциях.

function swap(&$left, &$right): void
{
    if ($left === $right) {
        return;
    }

    $tmp = $left;
    $left = $right;
    $right = $tmp;
}

$a = 1;
$b = 2;
var_dump(swap($a, $b), $a, $b);

//Видимость констант класса
class ConstDemo
{
    const PUBLIC_CONST_A = 1;
    public const PUBLIC_CONST_B = 2;
    protected const PROTECTED_CONST = 3;
    private const PRIVATE_CONST = 4;
}

//Обработка нескольких исключений в одном блоке catch
try {
    // Какой то код
} catch (FirstException | SecondException $e) {
    // Обрабатываем оба исключения
}

//Поддержка ключей в list()
//Теперь вы можете указывать ключи в операторе list() или в его новом коротком синтаксисе []. 
//Это позволяет деструктурировать массивы с нечисловыми или непоследовательными ключами.

$data = [
    ["id" => 1, "name" => 'Tom'],
    ["id" => 2, "name" => 'Fred'],
];

// стиль list()
list("id" => $id1, "name" => $name1) = $data[0];

// стиль []
["id" => $id1, "name" => $name1] = $data[0];

// стиль list()
foreach ($data as list("id" => $id, "name" => $name)) {
    // logic here with $id and $name
}

// стиль []
foreach ($data as ["id" => $id, "name" => $name]) {
    // logic here with $id and $name
}