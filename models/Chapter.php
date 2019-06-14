<?php namespace Jc91715\Book\Models;

use Model;
use Markdown;
/**
 * Chapter Model
 */
class Chapter extends Model
{
    use \October\Rain\Database\Traits\NestedTree;
    use \October\Rain\Database\Traits\SoftDelete;
    use \October\Rain\Database\Traits\Revisionable;

    const STATE_NO_CLAIM='no_claim';
    const STATE_TRANSLATING='translating';
    const STATE_REVIEWING='reviewing';
    const STATE_UNFINISHED_TRANSLATION='unfinished_translation';
    const STATE_FINISHED_TRANSLATION='finished_translation';

    public static $stateMaps = [
        ''=>'',
        self::STATE_NO_CLAIM=>'未认领',
        self::STATE_TRANSLATING=>'正在翻译中...',
        self::STATE_REVIEWING=>'正在审阅中...',
        self::STATE_UNFINISHED_TRANSLATION=>'翻译未完成，继续翻译...',
        self::STATE_FINISHED_TRANSLATION=>'翻译已完成',
    ];


    protected $dates = ['deleted_at'];
    public $revisionableLimit = 100000;
    protected $revisionable = ['history_content'];
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

    protected $appends = ['stateDesc'];
    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [
        'doc'=>[
            Doc::class,
            'key' => 'doc_id'
        ],
        'user'=>[
            \RainLab\User\Models\User::class,

        ]
    ];
    public $belongsToMany = [
        'users'=>[
            \RainLab\User\Models\User::class,
             'table' => 'jc91715_book_user_chapters'
        ]
    ];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [
        'revision_history' => ['System\Models\Revision', 'name' => 'revisionable']
    ];
    public $attachOne = [];
    public $attachMany = [];

    public function beforeSave()
    {
        if(!$this->slug){
            $this->slug = uniqid().time();
        }
        $this->content_html = self::formatHtml($this->content);
        $this->origin_html = self::formatHtml($this->origin);
        $this->history_html = self::formatHtml($this->history_content);
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

    public function getRevisionableUser()
    {
        return $this->user_id;
    }

    public function getStateDescAttribute()
    {
        return static::$stateMaps[$this->state];
    }

    public function canTranslated()
    {
        return in_array($this->state,[self::STATE_NO_CLAIM,self::STATE_UNFINISHED_TRANSLATION]);
    }

    public function isTranslating()
    {
        return $this->state==self::STATE_TRANSLATING;
    }
}
