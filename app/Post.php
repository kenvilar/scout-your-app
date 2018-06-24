<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Post extends Model
{
    use Searchable;

    /**
     * Customize the elements that will be searchable
     * @return array
     */
    /*public function toSearchableArray()
    {
        return [
            'title',
            'body',
        ];
    }*/

    protected $fillable = [
        'title',
        'body',
        'published'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
