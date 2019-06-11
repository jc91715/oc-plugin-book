<?php namespace Jc91715\Book\Models;

use Model;

/**
 * doc Model
 */
class Doc extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'jc91715_book_docs';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [
        'chapters' =>[
            Chapter::class,
            'key' => 'doc_id',
            'otherKey' => 'id',
        ]
    ];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];
}
