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
