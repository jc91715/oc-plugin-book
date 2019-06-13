<?php namespace Jc91715\Book\Models;

use Model;
use Markdown;
/**
 * doc Model
 */
class Doc extends Model
{
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];
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


    public function beforeSave()
    {
        $this->content_html = self::formatHtml($this->content);
        $this->origin_html = self::formatHtml($this->origin);
    }


    public static function formatHtml($input, $preview = false)
    {
        $result = Markdown::parse(trim($input));


        $result = str_replace('<pre>', '<pre class="prettyprint">', $result);

        return $result;
    }
}
