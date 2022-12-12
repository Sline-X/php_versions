<?php 
//Перечисления

enum Status
{
    case Draft;
    case Published;
    case Archived;
}
function acceptStatus (Status $status) {...}
//Используйте перечисления вместо набора констант, чтобы валидировать их 
//автоматически во время выполнения кода. 


//было
class Status
{
    const DRAFT = 'draft';
    const PUBLISHED = 'published';
    const ARCHIVED = 'archived';
}
function acceptStatus(string $status) {...}



<?php
//Readonly-свойства

class BlogData
{
    public readonly Status $status;

    public function __construct(Status $status)
    {
        $this->status = $status;
    }
}

//было 
class BlogData
{
    private Status $status;

    public function __construct(Status $status)
    {
        $this->status = $status;
    }

    public function getStatus(): Status 
    {
        return $this->status;
    }
}
//Readonly-свойства нельзя изменить после инициализации (т.е. когда им было присвоено значение).
//Они будут крайне полезны при реализации объектов типа VO и DTO.
//VO - объект-значение
//DTO - объект передачи данных


//смысл ключевого слова readonly в том, что после того, как свойство установлено, его больше нельзя переопределить
//можно было сделать вот так
class BlogData
{
    //Свойства, доступные только для чтения могут быть только типизированными:
    public readonly string $title;
    public readonly Status $status;
    // public readonly $mixed; // Ошибка: Нельзя использовать не типизированное readonly-свойство, 
    // но в php 8 появился тип mixed - значение может иметь любой тип, и в итоге будет правильно:
    //public readonly mixed $mixed;
    //Причина этого ограничения заключается в том, что, опуская тип свойства, PHP автоматически устанавливает значение null, 
    //если в конструкторе не было определено явное значение. Такое поведение в сочетании со свойством, доступным только для чтения вызовет ненужную путаницу.
    public function __construct(string $title, Status $status)
    {
        $this->title = $title;
        $this->status = $status;
    }

    //из нового можно даже вот так писать конструктор
    public function __construct(
        public readonly string $title,
        public readonly Status $status,
    ) {

    }
}


$blog = new BlogData(
    title: 'readonly',
    statuus: Status::PUBLISHED
);

$blog->title = 'change'; //ошибка, нельзя переопределить readonly

//Знание, что когда объект инициализирован, он больше не будет меняться, 
//даёт нам определённый уровень уверенности и спокойствия при написании кода: 
//целый ряд непредвиденных изменений данных просто не может произойти.

//У свойств, доступных только для чтения не может быть значения по умолчанию:
public readonly string $title = 'Свойства, доступные только для чтения'; // Ошибка: Нельзя использовать значение по умолчанию
//если это не свойство, определяемое в конструкторе:
public function __construct(
    public readonly string $title = 'Свойства, доступные только для чтения', 
) {}
//Причина, по которой это разрешено для свойств, определяемых в конструкторе, заключается в том, 
//что значение по умолчанию в этом случае используется не в качестве значения по умолчанию для свойства класса, 
//а только для аргумента конструктора. Под капотом приведённый выше код будет преобразован в этот:
public readonly string $title;
    
public function __construct(
    string $title = 'Свойства, доступные только для чтения', 
    ) {
        $this->title = $title;
    }
//Причина запрета использования значений по умолчанию для свойств, доступных только для чтения, заключается в том, 
//что в таком виде они ничем не будут отличаться от констант.


//Нельзя изменять флаг readonly при наследовании:
class Foo
{
    public readonly int $prop;
}
class Bar extends Foo
{
    public int $prop; // Ошибка: Нельзя изменять флаг readonly
}

//Правило действует в обоих направлениях: вам не разрешено добавлять или удалять флаг readonly при наследовании.
//Unset не допускается

//После того как свойство, доступное только для чтения установлено, вы не можете его изменить и даже сбросить:
$foo = new Foo('value');
unset($foo->prop); // Ошибка: Нельзя сбросить свойство, доступное только для чтения
?>


<?php
//Callback-функции как объекты первого класса

$foo = $this->foo(...);

$fn = strlen(...);
//было
$foo = [$this, 'foo'];

$fn = Closure::fromCallable('strlen');

//С помощью нового синтаксиса любая функция может выступать в качестве объекта первого класса. 
//Тем самым она будет рассматриваться как обычное значение, которое можно, например, сохранить в переменную.

?>

<?php
//Расширенная инициализация объектов

class Service 
{
    private Logger $logger;

    public function __construct(
        Logger $logger = new NullLogger(),
    ) {
        $this->logger = $logger;
    }
}

//было 
class Service
{
    private Logger $logger;

    public function __construct(
        ?Logger $logger = null,
    ) {
        $this->logger = $logger ?? new NullLogger();
    }
}

//Объекты теперь можно использовать в качестве значений параметров по умолчанию, статических переменных и глобальных констант, а также в аргументах атрибутов.

//Таким образом появилась возможность использования вложенных атрибутов.

class User
{
    #[\Assert\All(
        new \Assert\NotNull,
        new \Assert\Length(min: 5))
    ]
    public string $name = '';
}

//было
class User 
{
    /**
     * @Assert\All({
     *      @Assert\NotNull,
     *      @Assert\Length(min=5)
     * })
     */
    public string $name = '';
}
?>

<?php
//Пересечение типов

function count_and_iterate(Iterator&Countable $value)
{
    foreach ($value as $val) {
        echo $val;
    }

    count($value);
}

//было
function count_and_iterate(Iterator $value) {
    if(!($value instanceof Countable)) {
        throw new TypeError('value must be Countable')
    }

    foreach ($value as $val) {
        echo $val;
    }

    count($value);
} 

//Теперь в объявлении типов параметров можно указать, что значение должно относиться к нескольким типам одновременно.
//В данный момент пересечения типов нельзя использовать вместе с объединёнными типами, например, A&B|C.
 ?>

 <?php
 //Тип возвращаемого значения never

 function redirect(string $uri): never {
    header('Location: ' . $uri);
    exit();
 }

 function redirectToLoginPage(): never {
    redirect('/login');
    echo 'Hello'; // <- dead code detected by static analysis
 }

 //было
function redirect(string $uri) {
    header('Location: ' . $uri);
    exit();
}

function redirectToLoginPage() {
    redirect('/login');
    echo 'Hello'; // <- dead code
}

//Функция или метод, объявленные с типом never, указывают на то, 
//что они не вернут значение и либо выбросят исключение, либо завершат выполнение скрипта
//с помощью вызова функции die(), exit(), trigger_error() или чем-то подобным.
?>

<?php
//Окончательные константы класса

class Foo
{
    final public const XX = 'foo';
}

class Bar extends Foo
{
    public const XX = 'bar'; //Fatal error
}

//было
class Foo 
{
    public const XX = 'foo';
}

class Bar extends Foo
{
    public const XX = 'bar'; //no error
}
//Теперь константы класса можно объявить как окончательные (final), 
//чтобы их нельзя было переопределить в дочерних классах.
?>

<?php
//Явное восьмеричное числовое обозначение

0o16 === 16; // false — not confusing with explicit notation
0o16 === 14; // true

//было

016 === 16; // false because `016` is octal for `14` and it's confusing
016 === 14; // true

//Теперь можно записывать восьмеричные числа с явным префиксом 0o.
?>

<?php
//Файберы


$response = $httpClient->request('https://example.com/');
print json_decode($response->getBody()->buffer())['code'];

//было

$httpClient->request('https://example.com/')
        ->then(function (Response $response) {
            return $response->getBody()->buffer();
        })
        ->then(function (string $responseBody) {
            print json_decode($responseBody)['code'];
        });
/**
 * Файберы — это примитивы для реализации облегчённой невытесняющей конкурентности. 
 * Они являются средством создания блоков кода, которые можно приостанавливать и возобновлять, 
 * как генераторы, но из любой точки стека. Файберы сами по себе не предоставляют 
 * возможностей асинхронного выполнения задач, всё равно должен быть цикл обработки событий. 
 * Однако они позволяют блокирующим и неблокирующим реализациям использовать один и тот же API.
 * Файберы позволяют избавиться от шаблонного кода, который ранее использовался с помощью
 *  Promise::then() или корутин на основе генератора. Библиотеки обычно создают 
 * дополнительные абстракции вокруг файберов, поэтому нет необходимости взаимодействовать
 * с ними напрямую.
 */

?>

<?php
//Поддержка распаковки массивов со строковыми ключами

$arrayA = ['a' => 1];
$arrayB = ['b' => 2];

$result = ['a' => 0, ...$arrayA, ...$arrayB];

// ['a' => 1, 'b' => 2]


//было
$arrayA = ['a' => 1];
$arrayB = ['b' => 2];

$result = array_merge(['a' => 0], $arrayA, $arrayB);

// ['a' => 1, 'b' => 2]
//PHP раньше поддерживал распаковку массивов с помощью оператора ..., 
//но только если массивы были с целочисленными ключами. Теперь можно 
//также распаковывать массивы со строковыми ключами.




//Улучшения производительности
//Результат (относительно PHP 8.0):
//Ускорение демо-приложения Symfony на 23,0%
//Ускорение WordPress на 3,5%
/**
 * 
 *
*Функциональность с улучшенной производительностью в PHP 8.1:

 *   Бэкенд JIT для ARM64 (AArch64).
 *   Кеш наследования (не требуется связывать классы при каждом запросе).
 *   Ускорено разрешение имени класса (исключены преобразование регистра имени и поиск по хешу).
 *   Улучшения производительности timelib и ext/date.
 *   Улучшения итераторов файловой системы SPL.
 *   Оптимизация функций serialize()/unserialize().
 *   Оптимизация некоторых внутренних функций (get_declared_classes(), explode(), strtr(), strnatcmp(), dechex()).
 *   Улучшения и исправления JIT.

 *   Новые классы, интерфейсы и функции
¶

 *   Добавлен новый атрибут #[ReturnTypeWillChange].
 *   Добавлены функции fsync и fdatasync.
 *   Добавлена новая функция array_is_list.
 *   Новые функции Sodium XChaCha20.

 *   Устаревшая функциональность и изменения в обратной совместимости

 *   Передача значения NULL параметрам встроенных функций, не допускающим значение NULL, объявлена устаревшей.
 *   Предварительные типы возвращаемых значений во встроенных методах классов PHP
 *   Интерфейс Serializable объявлен устаревшим.
 *   Функции по кодированию/декодированию HTML-сущностей по умолчанию преобразуют одинарные кавычки и заменяют недопустимые символы на символ замены Юникода.
 *   Ограничены способы использования переменной $GLOBALS.
 *   Модуль MySQLi: режим ошибок по умолчанию установлен на выбрасывание исключения.
 *   Неявное преобразование числа с плавающей точкой к целому с потерей ненулевой дробной части объявлено устаревшим.
 *   Модуль finfo: ресурсы file_info заменены на объекты finfo.
 *   Модуль IMAP: ресурсы imap заменены на объекты IMAP\Connection.
 *   Модуль FTP: ресурсы Connection заменены на объекты FTP\Connection.
 *   Модуль GD: Font identifiers заменены на объекты GdFont.
 *   Модуль LDAP: ресурсы заменены на объекты LDAP\Connection, LDAP\Result и LDAP\ResultEntry.
 *   Модуль PostgreSQL: ресурсы заменены на объекты PgSql\Connection, PgSql\Result и PgSql\Lob.
 *   Модуль Pspell: ресурсы pspell, pspell config заменены на объекты PSpell\Dictionary, PSpell\Config.
 */