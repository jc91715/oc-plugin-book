<?php namespace Jc91715\Book\Components;

use Cms\Classes\ComponentBase;
use Jc91715\Book\Models\Chapter;
use Jc91715\Book\Models\Section;
use Markdown;

class Translate extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'translate Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [

        ];
    }

    public function addAssets(){
        $coreBuild = \System\Models\Parameter::get('system::core.build', 1);
        $this->addCss('/modules/system/assets/ui/storm.css?v' . $coreBuild);
        $this->addJs('/modules/system/assets/ui/storm-min.js?v' . $coreBuild);
        $this->addJs('/modules/backend/assets/js/october-min.js?v' . $coreBuild);
        $this->addJs('/modules/backend/formwidgets/codeeditor/assets/js/build-min.js?v' . $coreBuild);
        $this->addJs('/modules/backend/formwidgets/markdowneditor/assets/js/markdowneditor.js?v' . $coreBuild);
//        $this->addCss('/modules/backend/formwidgets/markdowneditor/assets/css/markdowneditor.css');
        $this->addCss('assets/css/markdowneditor.css');

    }
    protected function classTypes()
    {

        return [
            'chapters'=>Chapter::class,
            'sections'=>Section::class,
        ];
    }
    public function onRun()
    {

        $this->addAssets();
        $slug = $this->param('slug');

        $classType = $this->param('class_type');
        $type = $this->param('type');

        $classTypes = $this->classTypes();
        if(!isset($classTypes[$classType])){
            abort(404);
        }

        $chapter = $classTypes[$classType];
        $chapter = new $chapter;
        if($type){
            if(!$chapter->verifyType($type)){
                abort(404);
            }
        }
        $chapter = $chapter->where('slug',$slug)->first();

        if(!$chapter){
            abort(404);
        }
        $user = \Auth::getUser();


        if($chapter->canTranslated()){

            if($chapter->hasTranslatingCount($user)>=$chapter::TRANSLATING_COUNT_LIMIT){
                \Flash::success('您有未完成的翻译');
            }elseif ($chapter->hasReviewingCount($user)>=$chapter::REVIEWING_COUNT_LIMIT){
                \Flash::success('您有翻译正在审阅...要注意休息奥');
            }elseif ($chapter->hasReTranslatingCount($user)>=$chapter::RE_TRANSLATING_COUNT_LIMIT){
                \Flash::success('您有翻译正在重译...');
            } else{
                $chapter->translating($user,$type);
            }

        }
        $this->page['user'] = $user;
        $this->page['chapter'] = $chapter;
        $this->page['datavendorpath'] = asset('/modules/backend/formwidgets/codeeditor/assets/vendor/ace');
    }

    public function onRefresh()
    {
        $value = post('history_content');
        $previewHtml = $this->safe
            ? Markdown::parseSafe($value)
            : Markdown::parse($value);

        return [
            'preview' => $previewHtml
        ];
    }

    public function onSave(){
        $type = post('type');
        $id = post('id');
        $history_content = post('history_content');
        if(!$history_content){
            \Flash::error('内容不能为空');
            return [];
        }

        //验证是否存在该类
        $classType = $this->param('class_type');
        $classTypes = $this->classTypes();
        if(!isset($classTypes[$classType])){
            abort(404);
        }

        $chapter = $classTypes[$classType];
        $chapter = new $chapter;

        if(!$chapter=$chapter->find($id)){
            abort(404);
        }
        if(!$chapter->isTranslating()){
            \Flash::error('状态错误');
            return [];
        }
        $user = \Auth::getUser();
        if($user->id!=$chapter->user_id){
            return response()->status(403);
        }
        $state = '';
        switch ($type){
            case 1:
                //可不开放保存功能
                \Flash::success('保存成功');
                $state=$chapter::STATE_TRANSLATING;
                $chapter->submitToSave($state,$history_content);
                break;
            case 2:
                \Flash::success('请等待后台审阅');
                $state=$chapter::STATE_REVIEWING;
                $chapter->submitToReviewing($state,$history_content);
                break;
            default:
                abort(404);
                break;
        }


        return redirect()->refresh();

    }
}
