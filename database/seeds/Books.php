<?php

use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Author;
use App\Models\Category;

class Books extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 0; $i < 100; $i++) {
            $firs_author = Author::inRandomOrder()->first();
            $second_author = Author::inRandomOrder()->where('id', '!=', $firs_author->id)->first();
            $firs_category = Category::inRandomOrder()->first();
            $second_category = Category::inRandomOrder()->where('id', '!=', $firs_category->id)->first();
            $book = new Book();
            $book->name = 'Lorem ipsum dolor sit amet ' . $i;
            $book->photo_url = "";
            $book->save();
            $book->authors()->sync([$firs_author->id, $second_author->id]);
            $book->categories()->sync([$firs_category->id, $second_category->id]);
        }
    }
}
