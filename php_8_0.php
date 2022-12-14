<?php
//именованные аргументы, union type, атрибуты, упрощённое определение свойств в конструкторе, 
//выражение match, оператор nullsafe, JIT и улучшения в системе типов, обработке ошибок и консистентности. 

//Именованные аргументы
htmlspecialchars($string, double_encode: false)

//было
htmlspecialchars($string, ENT_COMPAT | ENT_HTML401, 'UTF-8', false);
/**
 * Указывайте только необходимые параметры, пропускайте необязательные.
 * Порядок аргументов не важен, аргументы самодокументируемы.
 */



//Атрибуты
class PostController
{
    #[Route("/api/posts/{id}", methods: ["GET"])]
    public function get($id) { /*...*/ }
}

//было 
class PostController
{
    /**
     * @Route("/api/posts/{id}", methods={"GET"})
     */
public function get($id) { /*...*/ }
}

//Вместо аннотаций PHPDoc вы можете использовать структурные метаданные с нативным синтаксисом PHP.

//Объявления свойств в конструкторе

class Point {
    public function __construct(
        public float $x = 0.0,
        public float $y = 0.0,
        public float $z = 0.0,
    ) {}
}

//было
class Point {
    public float $x;
    public float $y;
    public float $z;

    public function __construct(
        float $x = 0.0,
        float $y = 0.0,
        float $z = 0.0
    ) {
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
    }
}
//Меньше шаблонного кода для определения и инициализации свойств.

//Тип Union
class Number {
    public function __construct(
        private int|float $number
    ) {}
}

new Number('NaN'); // TypeError

//было
class Number {
    /** @var int|float */
    private $number;

    /**
     * @param float|int $number
     */
    public function __construct($number)
    {
        $this->number = $number;
    }
}

new Number('NaN'); //нет ошибки\

//Вместо аннотаций PHPDoc для объединённых типов вы можете использовать объявления типа union, которые проверяются во время выполнения.


//Выражение Match
echo match (8.0) {
    '8.0' => 'Bruh',
    8.0 => 'Nice',
};
//> Nice

//было
switch (8.0) {
    case '8.0':
        $result = 'Bruh';
        break;
    case 8.0:
        $result = 'Nice';
        break;
}
echo $result;
//> Bruh

//Новое выражение match похоже на оператор switch со следующими особенностями:
//Match — это выражение, его результат может быть сохранён в переменной или возвращён.
//Условия match поддерживают только однострочные выражения, для которых не требуется управляющая конструкция break;.
//Выражение match использует строгое сравнение.


//Оператор Nullsafe
$country =
$session?->user?->getAddress()?->country;

//было
$country = null;

if ($session !== null) {
    $user = $session->user;

    if ($user !== null) {
        $address = $user->getAddress();

        if ($address !== null) {
            $country = $address->country;
        }
    }
}
//Вместо проверки на null вы можете использовать последовательность вызовов с новым оператором Nullsafe. 
//Когда один из элементов в последовательности возвращает null, 
//выполнение прерывается и вся последовательность возвращает null.


//Улучшенное сравнение строк и чисел
0 == 'foobar' //false

//было
0 == 'foobar' //true

//При сравнении с числовой строкой PHP 8 использует сравнение чисел. 
//В противном случае число преобразуется в строку и используется сравнение строк.


//Ошибки согласованности типов для встроенных функций

strlen([]); // TypeError: strlen(): Argument #1 ($str) must be of type string, array given

array_chunk([], -1); // ValueError: array_chunk(): Argument #2 ($length) must be greater than 0

//было

strlen([]); // Warning: strlen() expects parameter 1 to be string, array given

array_chunk([], -1); // Warning: array_chunk(): Size parameter expected to be greater than 0

//Большинство внутренних функций теперь выбрасывают исключение Error, если при проверке параметра возникает ошибка.
?>

PHP 8 представляет два механизма JIT-компиляции. Трассировка JIT, наиболее перспективная из них, 
на синтетических бенчмарках показывает улучшение производительности примерно в 3 раза и в 1,5–2 раза на некоторых долго работающих приложениях. 
Стандартная производительность приложения находится на одном уровне с PHP 7.4. 

Улучшения в системе типов и обработке ошибок

Более строгие проверки типов для арифметических/побитовых операторов RFC
Проверка методов абстрактных трейтов RFC
Правильные сигнатуры магических методов RFC
Реклассификация предупреждений движка RFC
Фатальная ошибка при несовместимости сигнатур методов RFC
Оператор @ больше не подавляет фатальные ошибки.
Наследование с private методами RFC
Новый тип mixed RFC
Возвращаемый тип static RFC
Типы для стандартных функций E-mail Тема
Непрозрачные объекты вместо ресурсов для Curl, Gd, Sockets, OpenSSL, XMLWriter, e XML расширения

Прочие улучшения синтаксиса

Разрешена запятая в конце списка параметров RFC и в списке use замыканий RFC
Блок catch без указания переменной RFC
Изменения синтаксиса переменных RFC
Имена в пространстве имен рассматриваются как единый токен RFC
Выражение Throw RFC
Добавление ::class для объектов RFC


Новые классы, интерфейсы и функции

Класс Weak Map
Интерфейс Stringable
str_contains(), str_starts_with(), str_ends_with()
fdiv()
get_debug_type()
get_resource_id()
Объектно-ориентированная функция token_get_all()
Новые API для обходения и обработки DOM
