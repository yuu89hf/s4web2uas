<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WikiArticle extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'extract',
        'thumbnail_url',
        'page_url',
        'fetched_at',
    ];

    protected $casts = [
        'fetched_at' => 'datetime',
    ];
}