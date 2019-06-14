<?php namespace Jc91715\Book\Components;

use Cms\Classes\ComponentBase;
use Jc91715\Book\Models\Chapter;
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
    public function onRun()
    {

        $this->addAssets();
        $slug = $this->param('slug');

        $chapter = Chapter::where('slug',$slug)->first();

        if(!$chapter){
            abort(404);
        }
        $user = \Auth::getUser();


        if($chapter->canTranslated()){
            $chapter->user_id = $user->id;
            $chapter->state = Chapter::STATE_TRANSLATING;
            $chapter->claim_time = date('Y-m-d H:i:s');
            $chapter->save();
            $chapter->users()->attach($user->id);
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
        if(!$chapter=Chapter::find($id)){
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
                \Flash::error('保存成功');
                $state=Chapter::STATE_TRANSLATING;
                break;
            case 2:
                \Flash::error('请等待后台审阅');
                $state=Chapter::STATE_REVIEWING;
                break;
            default:
                abort(404);
                break;
        }
        $chapter->history_content = $history_content;
        $chapter->state = $state;
        $chapter->save();

        return redirect()->refresh();

    }
}
