<?php namespace Jc91715\Book\FormWidgets;

use Backend\Classes\FormField;
use Backend\Classes\FormWidgetBase;
use System\Models\Revision;
use Jc91715\Book\Models\Chapter;
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

     //
     // AJAX handlers
     //
     public function onRevertHistory(){
        $revision_id = Input::get('revision_id');
        $section_id  = Input::get('chapter_id');

        $chapter = Chapter::find($section_id);
        $history = Revision::find($revision_id);



        if($this->model->revision_history()->latest('id')->first()->id==$revision_id){
            $chapter->content = $history->new_value;
        }else{
            $chapter->content = $history->new_value;
            $chapter->history_content = $history->new_value;
        }

        $chapter->save();

        Flash::success('设置成功');
     }
}
