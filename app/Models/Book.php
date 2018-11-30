<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class Book extends Model
{

    /**
     * @var array $hidden
     */
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    /**
     * get authors
     */
    public function authors()
    {
        return $this->belongsToMany('App\Models\Author');
    }

    /**
     * get categories
     */
    public function categories()
    {
        return $this->belongsToMany('App\Models\Category', 'category_book', 'book_id', 'category_id');
    }

    /**
     * @return mixed
     */
    public function getCategoryIdsAttribute()
    {
        return $this->categories()->pluck('id')->toArray();
    }

    /**
     * @return mixed
     */
    public function getAuthorIdsAttribute()
    {
        return $this->authors()->pluck('id')->toArray();
    }

    /**
     * @param string $type
     * @return string
     */
    public static function publicPathForImage($type = 'link')
    {
        switch ($type) {
            case 'path':
                return public_path('images/books/');
            case 'link':
                return url('image/books/');
            default:
                return '';
        }
    }

    /**
     * @param UploadedFile $file
     * @return bool
     */
    public function setNewPhoto(UploadedFile $file)
    {
        if (!empty($file)) {
            @unlink(self::publicPathForImage('path') . $this->photo);
            $i = 0;
            do {
                $i++;
                $new_name = mb_strimwidth(str_slug($this->name), 0, 40) . '-' . $i . '.' . $file->getClientOriginalExtension();
            } while (file_exists(self::publicPathForImage('path') . $new_name));
            $file->move(self::publicPathForImage('path'), $new_name);
            $this->photo = $new_name;
        }
        return false;
    }
}
