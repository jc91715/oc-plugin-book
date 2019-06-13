<?php namespace Jc91715\Book\Models;

use Model;
use Markdown;
/**
 * Chapter Model
 */
class Chapter extends Model
{
    use \October\Rain\Database\Traits\NestedTree;
    /**
     * @var string The database table used by the model.
     */
    public $table = 'jc91715_book_chapters';

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
    public $hasMany = [];
    public $belongsTo = [
        'doc'=>[
            Doc::class,
            'key' => 'doc_id'
        ]
    ];
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

    public function scopeFilterBooks($query, $books)
    {
        return $query->whereHas('doc', function($q) use ($books) {
            $q->whereIn('id', $books);
        });
    }
}
