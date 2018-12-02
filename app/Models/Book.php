<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use App\Models\Traits\LinkBuilder;

class Book extends Model
{
    use LinkBuilder;

    /**
     * @var int $perPage
     */
    protected $perPage = 10;

    /**
     * @var array $hidden
     */
    protected $hidden = [
        'created_at', 'updated_at'
    ];

    /**
     * Book constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->route = 'books';
    }

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
     * @return string
     */
    public function getPhotoUrl()
    {
        if (!empty($this->photo)) {
            return self::publicPathForImage() . $this->photo;
        }
        return '';
    }

    /**
     * @param string $type
     * @return string
     */
    public static function publicPathForImage($type = 'link')
    {
        switch ($type) {
            case 'path':
                return public_path('images/books') . '/';
            case 'link':
                return url('images/books') . '/';
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
            $this->deletePhoto();
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

    /**
     * setter for $title_for_route in trait LinkBuilder
     */
    public function getTitleForRouteAttribute()
    {
        $this->title_for_route = $this->name;
    }

    public function deletePhoto()
    {
        @unlink(self::publicPathForImage('path') . $this->photo);
    }
}
