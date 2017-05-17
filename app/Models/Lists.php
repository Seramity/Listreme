<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Michelf\Markdown;


class Lists extends Model
{
    protected $table = 'lists';

    protected $fillable = [
        'uid',
        'title',
        'content',
        'category',
        'size'
    ];

    public $MAX_TITLE_CHAR = 48;
    public $MAX_CONTENT_CHAR = 10000;
    public $MAX_CATEGORY_CHAR = 24;

    public $DEFAULT_SIZE = 1; // 0: SMALL, 1: MEDIUM, 2: LARGE


    public function markdownContent()
    {
        return Markdown::defaultTransform($this->content);
    }

}