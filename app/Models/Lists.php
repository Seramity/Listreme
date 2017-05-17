<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Michelf\Markdown;

/**
 * Class Lists
 *
 * List model that extends off of the Eloquent package.
 * Deals with SQL tasks and other various functions for Lists.
 *
 * @package App\Models
 */
class Lists extends Model
{
    /**
     * Usable Lists database columns.
     *
     * @var array $fillable
     */
    protected $fillable = [
        'uid',
        'title',
        'content',
        'category',
        'size'
    ];

    /**
     * Variables used for validation.
     *
     * @var int $MAX_TITLE_CHAR
     * @var int $MAX_CONTENT_CHAR
     * @var int $MAX_CATEGORY_CHAR
     */
    public $MAX_TITLE_CHAR = 48;
    public $MAX_CONTENT_CHAR = 10000;
    public $MAX_CATEGORY_CHAR = 24;

    /**
     * Default display size for Lists in HTML.
     *
     * @var int $DEFAULT_SIZE
     */
    public $DEFAULT_SIZE = 1; // 0: SMALL, 1: MEDIUM, 2: LARGE

    /**
     * Takes list content and throws it through a markdown parser.
     *
     * @return string
     */
    public function markdownContent()
    {
        return Markdown::defaultTransform($this->content);
    }

}