<?php namespace Jc91715\Book\FormWidgets;

use Backend\Classes\FormField;
use Backend\Classes\FormWidgetBase;
use System\Models\Revision;
use RainLab\User\Models\User;
use Flash;
use Input;
use Lang;

/**
 * RevisionHistory Form Widget
 */
class RevisionHistory extends FormWidgetBase
{
    //
    // Configurable properties
    //


    /**
     * {@inheritDoc}
     */
    protected $defaultAlias = 'jc91715_book_revision_history';

    /**
     * {@inheritDoc}
     */
    public function init()
    {
    }

    /**
     * {@inheritDoc}
     */
    public function render()
    {
        $this->prepareVars();
        return $this->makePartial('revisionhistory');
    }

    /**
     * Prepares the form widget view data
     */
    public function prepareVars()
    {
        $this->vars['history'] = $this->model->revision_history;
        $this->vars['chapter_id'] = $this->model->id;
    }

    /**
     * {@inheritDoc}
     */
    public function loadAssets()
    {
        $this->addCss('css/revisionhistory.css', 'jc91715.book');
        $this->addJs('js/revisionhistory.js', 'jc91715.book');
    }

    /**
     * {@inheritDoc}
     */
     public function getSaveValue($value)
     {
         return FormField::NO_SAVE_DATA;
     }

    public function onReview()
    {
        $revision_id = Input::get('revision_id');
        $section_id  = Input::get('chapter_id');
        $state  = Input::get('state');

        $history = Revision::find($revision_id);
        $chapterClass = $history->revisionable_type;
        $chapterClass = new $chapterClass();
        $chapter =$chapterClass->find($section_id);

        if($chapter->canReview()){
            $user = User::find($chapter->user_id);
            switch ($state){
                case 'success':
                    $chapter->reviewSuccess($user);
                    \Flash::success('审阅成功：ok');
                    break;
                case 'fail':
                    $chapter->reviewFail($user);
                    \Flash::success('审阅失败：ok');
                    break;
                default:
                    \Flash::error('请检查错误');
                    break;
            }
            return ;
        }
        \Flash::error('状态错误');

    }

     //
     // AJAX handlers
     //
     public function onRevertHistory(){
        $revision_id = Input::get('revision_id');
        $section_id  = Input::get('chapter_id');



        $history = Revision::find($revision_id);
        $chapterClass = $history->revisionable_type;
         $chapterClass = new $chapterClass();
        $chapter =$chapterClass->find($section_id);


        if($chapter->revision_history()->latest('id')->first()->id==$revision_id){
            $chapter->content = $history->new_value;
        }else{
            $chapter->content = $history->new_value;
            $chapter->history_content = $history->new_value;
        }

        $chapter->save();

        Flash::success('设置成功');
     }


}
