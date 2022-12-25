<?php
//возвращаемый тип и типизация аргументов функции
//int, bool, string, float
function test (int $variable): int
{
    return $variable;
}

$a = 'Hello';
$b = 12;
echo test($a);

//Оператор объединения с null
function returnNew() {
	$nullVar;
	$notNullVar = 10;
	$var = 'nobody';
    // я раньше делал $notNullVar ? $notNullVar : $var;
    return $notNullVar ?? $var;
}
 
echo returnNew();
 
//Оператор spaceship
echo 1 <=> 1; //0  - равны
echo 1 <=> 2; //-1  - 1 меньше 2
echo 2 <=> 1; //1  - 2 больше 1

//можно и для строк, лексиграфическое сравнение


//определение констант массивов
define('User', [
    'name'     => 'Maksim',
    'lastName' => 'Kipa'
]);

echo User['name'];


//анонимные классы
var_dump(new class(10) {
    private $num;

    public function __construct($num)
    {
        $this->num = $num;
    }

});

//Синтаксис кодирования Unicode
echo "\u{004D}";
echo "\u{0061}";
echo "\u{006B}";
echo "\u{0073}";

//десериализация с фильтрацией, даёт более высокий уровень безопасности 
//unserialize

//Эта функциональность обеспечивает более высокий уровень безопасности при десериализации объектов 
//с непроверенными данными. Это позволяет предотвратить возможную инъекцию кода, позволяя 
//разработчику использовать белый список классов для десериализации.


// Преобразование всех объектов в __PHP_Incomplete_Class
$data = unserialize($foo, ["allowed_classes" => false]);

// Преобразование всех объектов, кроме MyClass и MyClass2 в __PHP_Incomplete_Class
$data = unserialize($foo, ["allowed_classes" => ["MyClass", "MyClass2"]]);

// Поведение по умолчанию принимает все классы (можно просто не задавать второй аргумент)
$data = unserialize($foo, ["allowed_classes" => true]);


// ожидания assert, если перывй аргумент false, то можно получить исключение
//используется для тестов, например phpunit
ini_set('assert.exception', 1);

class CustomError extends AssertionError {}

assert(false, new CustomError('Сообщение об ошибке'));


//групповые объявления use, можно группировать из одного неймспейса
use some\namespace\{ClassA, ClassB, ClassC as C};
use function some\namespace\{fn_a, fn_b, fn_c};
use const some\namespace\{ConstA, ConstB, ConstC};

//было
use some\namespace\ClassA;
use some\namespace\ClassB;
use some\namespace\ClassC as C;

use function some\namespace\fn_a;
use function some\namespace\fn_b;
use function some\namespace\fn_c;

use const some\namespace\ConstA;
use const some\namespace\ConstB;
use const some\namespace\ConstC;

//Добавлена возможность доступа к методам и свойствам класса при клонировании,
// то есть (clone $foo)->bar().



//Deprecated
//конструкторы PHP4
class foo {
    function foo() {
        echo 'Я конструктор!';
    }
}

//Статические вызовы нестатических методов
class foo {
    function bar() {
        echo 'Я не статический!';
    }
}

foo::bar();