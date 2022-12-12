<?php

//readonly-классы
//Начиная с PHP 8.2.0, класс может быть помечен модификатором readonly. 
//Пометка класса как readonly добавит модификатор readonly к каждому объявленному свойству и предотвратит создание динамических свойств.
//Поскольку ни нетипизированные, ни статические свойства не могут быть помечены модификатором readonly, 
//классы, доступные только для чтения также не могут их объявлять:
//Класс readonly может быть расширен тогда и только тогда, когда дочерний класс также является классом readonly.  
readonly class BlogData
{
    public string $title;
    public Status $status;

    public function __construct(string $title, Status $status)
    {
        $this->title = $title;
        $this->status = $status;
    }
}

//до этого readonly свойства были добавлены в php 8.1
class BlogData
{
    public readonly string $title;
    public readonly Status $status;

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
?>

<?php
/**
 * Улучшенная система типов:
 * теперь можно использовать null и false как самостоятельные типы
 * добавлен тип true
 * Теперь можно комбинировать пересечение и объединение типов. Тип должен быть записан в виде ДНФ - дизъюнктивной нормальной формы
 */

 class Foo {
    public function bar((A&B)|null $entity) {
        return $entity;
    }
 }
 //ДНФ позволяет совместить объединение и пересечение типов, при этом обязательно типы пересечения следует сгруппировать скобками. 

 //было
 class Foo {
    public function bar(mixed $entity) {
        if ((($entity instanceof A) && ($entity instanceof B)) || ($entity === null)) {
            return $entity;
        }

        throw new Exception('Invalid entity');
    }
 }
?>

<?php
//Самостоятельные типы null, false и true
class Falsy
{
    public function alwaysFalse(): false {}

    public function alwaysTrue(): true {}

    public function alwaysNull(): null {}
}

//было class Falsy
{
    public function almostFalse(): bool {}

    public function almostTrue(): bool {}

    public function almostNull(): string|null {}
}
?>