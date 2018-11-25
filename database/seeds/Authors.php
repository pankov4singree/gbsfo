<?php

use Illuminate\Database\Seeder;
use App\Models\Author;

class Authors extends Seeder
{
    protected $authors = [
        ['Лев', 'Толстой'],
        ['Гюстав', 'Флобер'],
        ['Марк', 'Твен'],
        ['Джордж', 'Элиот'],
        ['Герман', 'Мелвилл'],
        ['Чарльз', 'Диккенс'],
        ['Фёдор', 'Достоевский'],
        ['Уильям', 'Фолкнер'],
        ['Генри', 'Джеймс'],
        ['Джейн', 'Остин'],
        ['Эрнест', 'Хемингуэй'],
        ['Франц', 'Кафка'],
        ['Уильям', 'Шекспир'],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->authors as $author) {
            (new Author(['first_name' => $author[0], 'last_name' => $author[0]]))->save();
        }
    }
}
