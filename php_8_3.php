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


//Новый атрибут #[\Override]

//было
use PHPUnit\Framework\TestCase;

final class MyTest extends TestCase {
    protected $logFile;
    
    protected function setUp(): void {
        $this->logFile = fopen('/tmp/logfile', 'w');
    }
    
    protected function taerDown(): void {
        fclose($this->logFile);
        unlink('tmp/logfile');
    }
}
// The log file will never be removed, because the
// method name waw mistyped (taerDown vs tearDown)

//стало
use PHPUnit\Framework\TestCase;

final class MyTest extends TestCase {
    protected $logFile;
    
    protected function setUp(): void {
        $this->logFile = fopen('/tmp/logfile', 'w');
    }
    
    #[\Override]
    protected function taerDown(): void {
        fclose($this->logFile);
        unlink('tmp/logfile');
    }
}
//Fatal error: MyTest::taerDown has #[\Override] attribute,
// but no matching parent method exists

// Если добавить методу атрибут #[\Override], то PHP убедится, что методс
// таким же именем существует в родительском классе или в реализованноминтерфейсе.
// Добавление атрибута даёт понять, что переопределение родительскогометода является
// намеренным, а также упрощает рефакторинг, поскольку удаление переопределённого
// родительского метода будет обнаружено.


//Глубокое клонирование readonly-свойств

//было
class PHP
{
    public string $version = '8.2';
}

readonly class Foo
{
    public function __construct(
        public PHP $php
    ) {
    }
    
    public function __clone(): void
    {
        $this->php = clone $this->php;
    }
}

$instance = new Foo(new PHP());
$cloned   = clone $instance;
//Fatal error: cannot modify readonly property Foo::$php

//стало
//... тот же класс Foo и PHP
$cloned->php->version = '8.3';

//Свойства, доступные только для чтения (readonly) теперь могут быть
// изменены один раз с помощью магического метода __clone для обеспечения
// возможности глубокого клонирования readonly-свойств.


//Новая функция json_validate()

//было
//самописная функция
function json_validate(string $string): bool {
    json_decode($string);
    
    return json_last_error() === JSON_ERROR_NONE;
}

var_dump(json_validate('{ "test": { "foo": "bar"} }')); //true

//стало
//встроенная в язык функция
var_dump(json_validate('{ "test": { "foo": "bar" } }')); // true

// Функция json_validate() позволяет проверить, является ли строка синтаксически
// корректным JSON, при этом она более эффективна, чем функция json_decode().
