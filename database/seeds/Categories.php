<?php

use Illuminate\Database\Seeder;
use App\Models\Category;

class Categories extends Seeder
{

    protected $categories = [
        'Художественная литература',
        'Компьютеры',
        'Деловая литература',
        'Наука, образование',
        'Школьные учебники',
        'Детская литература',
        'Семья, дом, дача',
        'Техника и технология',
        'Медицина, спорт, здоровье',
        'Специальная и справочная литература',
        'Искусство, культура',
        'Хобби, коллекционирование',
        'Мистика, эзотерика, непознанное',
        'Разное'
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->categories as $categories) {
            (new Category(['name' => $categories]))->save();
        }
    }
}
