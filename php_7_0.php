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

// Преобразование всех объектов в __PHP_Incomplete_Class
$data = unserialize($foo, ["allowed_classes" => false]);

// Преобразование всех объектов, кроме MyClass и MyClass2 в __PHP_Incomplete_Class
$data = unserialize($foo, ["allowed_classes" => ["MyClass", "MyClass2"]]);

// Поведение по умолчанию принимает все классы (можно просто не задавать второй аргумент)
$data = unserialize($foo, ["allowed_classes" => true]);
