<?php namespace Jc91715\Book\Models;

use Model;
use Markdown;

use RainLab\User\Models\User;
/**
 * section Model
 */
class Section extends Model
{
    use \October\Rain\Database\Traits\NestedTree;
    use \October\Rain\Database\Traits\SoftDelete;
    use \October\Rain\Database\Traits\Revisionable;

    //正在翻译的数量限制
    const TRANSLATING_COUNT_LIMIT=1;
    //正在审阅的数量限制
    const REVIEWING_COUNT_LIMIT=2;
    //正在重译的数量
    const RE_TRANSLATING_COUNT_LIMIT=1;
    //正在改进的数量
    const IMPROVING_COUNT_LIMIT=1;
    const STATE_NO_CLAIM='no_claim';
    const STATE_TRANSLATING='translating';
    const STATE_REVIEWING='reviewing';
    const STATE_UNFINISHED_TRANSLATION='unfinished_translation';
    const STATE_FINISHED_TRANSLATION='finished_translation';
    const STATE_RE_TRANSLATING='re_translating';
    const STATE_IMPROVING='improving';

    public static $stateMaps = [
        ''=>'',
        self::STATE_NO_CLAIM=>'我要翻译',
        self::STATE_TRANSLATING=>'正在翻译中...',
        self::STATE_REVIEWING=>'正在审阅中...',
        self::STATE_UNFINISHED_TRANSLATION=>'翻译未完成，继续翻译...',
        self::STATE_FINISHED_TRANSLATION=>'翻译已完成',
        self::STATE_RE_TRANSLATING=>'正在重译中',
        self::STATE_IMPROVING=>'正在改进中',
    ];

    public static $stateActionMaps = [
        ''=>'',
        self::STATE_NO_CLAIM=>'未认领',
        self::STATE_TRANSLATING=>'%s-%s:正在翻译中...',
        self::STATE_REVIEWING=>'%s-%s:提交了审阅...',
        self::STATE_UNFINISHED_TRANSLATION=>'%s-%s:审阅失败，翻译未完成.',
        self::STATE_FINISHED_TRANSLATION=>'%s-%s：审阅成功， 翻译已完成',
        self::STATE_RE_TRANSLATING=>'%s-%s：正在重译中',
        self::STATE_IMPROVING=>'%s-%s：正在改进中',
    ];

    protected $dates = ['deleted_at'];
    public $revisionableLimit = 100000;
    protected $revisionable = ['history_content'];

    /**
     * @var string The database table used by the model.
     */
    public $table = 'jc91715_book_sections';
    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];
    protected $appends = ['stateDesc','stateType'];
    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = ['chapter'=>Chapter::class,'doc'=>Doc::class,'user'=>[
        User::class,

    ]];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [
        'revision_history' => ['System\Models\Revision', 'name' => 'revisionable'],
    ];
    public $morphToMany = [
        'users'  => [
            User::class,
            'name' => 'userable',
            'table'=> 'jc91715_book_user_chapters',
            'timestamps'=>'true',
            'pivot'=>['claim_time','submit_to_review_time','extra','state','review_id'],
        ]
    ];
    public $attachOne = [];
    public $attachMany = [];



    public function beforeSave()
    {


        if(!$this->slug){
            $this->slug = uniqid().time();
        }
        if(!$this->doc_id){
            if($this->chapter){
                $this->doc_id = $this->chapter->doc_id;
            }

        }




        $this->content_html = self::formatHtml($this->content);
        $this->origin_html = self::formatHtml($this->origin);
        $this->history_html = self::formatHtml($this->history_content);
    }

    public function afterSave()
    {
        if($this->chapter){//同步分块数量
            $chapter = $this->chapter;
            $chapter->section_number = $chapter->sections()->get()->count();
            $chapter->translate_section_number = $chapter->sections()->where('state',self::STATE_FINISHED_TRANSLATION)->get()->count();
            $chapter->save();
        }
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
    public function scopeFilterChapters($query, $chapters)
    {
        return $query->whereHas('chapters', function($q) use ($chapters) {
            $q->whereIn('id', $chapters);
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
    public function getStateTypeAttribute()
    {
        $arr = [];
        switch ($this->state){
            case '':
                $arr[]=['type'=>self::STATE_TRANSLATING,'desc'=>'我要翻译','link'=>true];
                break;
            case self::STATE_NO_CLAIM:
                $arr[]=['type'=>self::STATE_TRANSLATING,'desc'=>'我要翻译','link'=>true];
                break;
            case self::STATE_UNFINISHED_TRANSLATION:
                $arr[]=['type'=>self::STATE_RE_TRANSLATING,'desc'=>'重译','link'=>true];
                break;
            case self::STATE_FINISHED_TRANSLATION:

                $arr[]=['type'=>self::STATE_IMPROVING,'desc'=>'改进','link'=>true];
                $arr[]=['type'=>self::STATE_RE_TRANSLATING,'desc'=>'重译','link'=>true];
                break;
            default:
                break;
        }
        if(empty($arr)){
            return null;
        }
        return $arr;
    }

    public  function verifyType($type)
    {
        return in_array($type,[self::STATE_TRANSLATING,self::STATE_RE_TRANSLATING,self::STATE_IMPROVING]);
    }
    public function canTranslated()
    {
        return in_array($this->state,['',self::STATE_NO_CLAIM,self::STATE_FINISHED_TRANSLATION,self::STATE_UNFINISHED_TRANSLATION]);
    }

    public function canReview()
    {
        return $this->state==self::STATE_REVIEWING;

    }

    public function hasTranslatingCount($user)
    {
        return static::newQuery()->where('user_id',$user->id)->where('state',self::STATE_TRANSLATING)->get()->count();
    }
    public function hasReviewingCount($user)
    {
        return static::newQuery()->where('user_id',$user->id)->where('state',self::STATE_REVIEWING)->get()->count();
    }
    public function hasReTranslatingCount($user)
    {
        return static::newQuery()->where('user_id',$user->id)->where('state',self::STATE_RE_TRANSLATING)->get()->count();
    }
    public function hasImprovingCount($user)
    {
        return static::newQuery()->where('user_id',$user->id)->where('state',self::STATE_IMPROVING)->get()->count();
    }

    public function translating($user,$type='')
    {


        switch ($type){
            case self::STATE_TRANSLATING:
                if(!$this->state||$this->state==self::STATE_NO_CLAIM){//审阅成功或失败传STATE_TRANSLATING限制进入这里。
                    $this->startTranslating($user);
                }
                break;
            case self::STATE_IMPROVING:
                $this->startImproving($user);
                break;
            case self::STATE_RE_TRANSLATING:
                $this->startReTranslating($user);
                break;
            default:
                break;
        }
    }
    public function isTranslating()
    {
        return in_array($this->state,[self::STATE_TRANSLATING,self::STATE_RE_TRANSLATING,self::STATE_IMPROVING]);
    }


    public function startTranslating($user)
    {
        $this->user_id = $user->id;
        $this->state = self::STATE_TRANSLATING;
//        $this->claim_time = date('Y-m-d H:i:s');
        $this->save();
        $this->users()->syncWithoutDetaching([$user->id=>['claim_time'=>date('Y-m-d H:i:s'),'state'=>$this->state]]);

        $this->recordActionHistory($user);
    }

    public function submitToSave($state,$history_content)
    {
        $this->state = $state;
        $this->history_content = $history_content;
        $this->save();

        $user = User::find($this->user_id);
        if($user){
            $this->recordActionHistory($user);
        }

    }

    public function submitToReviewing($state,$history_content)
    {
        $this->submitToSave($state,$history_content);
        $this->users()->updateExistingPivot($this->user_id,['submit_to_review_time'=>date('Y-m-d H:i:s'),'state'=>$this->state]);
        //todo 可在这里去给相关审阅人员发送通知
    }

    public function startReTranslating($user)
    {
        $this->user_id = $user->id;
        $this->state = self::STATE_RE_TRANSLATING;
        $this->save();

        $this->users()->syncWithoutDetaching([$user->id=>['re_translating_time'=>date('Y-m-d H:i:s'),'state'=>$this->state]]);
        $this->recordActionHistory($user);
    }

    public function startImproving($user)
    {
        $this->user_id = $user->id;
        $this->state = self::STATE_IMPROVING;
        $this->save();

        $this->users()->syncWithoutDetaching([$user->id=>['improving_time'=>date('Y-m-d H:i:s'),'state'=>$this->state]]);
        $this->recordActionHistory($user);
    }
    public function reviewSuccess($user)
    {
        $this->state=self::STATE_FINISHED_TRANSLATION;
        $this->content=$this->history_content;
        $this->save();
        $this->users()->syncWithoutDetaching([$this->user_id=>['review_time'=>date('Y-m-d H:i:s'),'state'=>$this->state,'review_id'=>$user->id]]);
        $this->recordActionHistory($user);
    }
    public function reviewFail($user)
    {
        $this->state=self::STATE_UNFINISHED_TRANSLATION;
        $this->save();
        $this->users()->syncWithoutDetaching([$this->user_id=>['review_time'=>date('Y-m-d H:i:s'),'state'=>$this->state,'review_id'=>$user->id]]);
        $this->recordActionHistory($user);
    }
    public function recordActionHistory($user)
    {
        $historyActionString =sprintf(self::$stateActionMaps[$this->state],date('Y-m-d H:i:s'),$user->name.$user->id);
        $usesExist = $this->users()->where('user_id',$this->user_id)->first();
        if($usesExist){
            $pivot = $usesExist->pivot;
            $extra = $pivot->extra;
            if(!$extra){
                $extra = [];
            }else{
                $extra = json_decode($extra);
            }
            $extra[] = $historyActionString;
            $pivot->extra = json_encode($extra);
            $pivot->save();
        }
    }

    public function getContentHtmlAttribute($val)
    {
        $val=str_replace('<code>','<code class="prettyprint">',str_replace('<pre>','<pre class="prettyprint">',$val));

        return str_replace('<h6>', '<h6 id="aaa'.$this->getRandomString(50).'">', str_replace('<h5>', '<h5 id="aaa'.$this->getRandomString(50).'">', str_replace('<h4>', '<h4 id="aaa'.$this->getRandomString(50).'">', str_replace('<h3>', '<h3 id="aaa'.$this->getRandomString(50).'">', str_replace('<h2>', '<h2 id="aaa'.$this->getRandomString(50).'">', str_replace('<h1>', '<h1 id="aaa'.$this->getRandomString(50).'">', $val))))));

    }
     public  function getRandomString($len, $chars=null)
    {
        if (is_null($chars)) {
            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        }
        $chars = str_shuffle($chars);
        mt_srand(10000000*(double)microtime());
        for ($i = 0, $str = '', $lc = strlen($chars)-1; $i < $len; $i++) {
            $str .= $chars[mt_rand(0, $lc)];
        }
        return $str;
    }
}
