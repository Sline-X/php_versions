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