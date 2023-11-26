<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute ;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'uuid',
        'description',
        'image',
    ];

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn($image) => asset('/storage/posts'.$image),
        );
    }
}
