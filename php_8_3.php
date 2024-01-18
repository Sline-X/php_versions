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
