<?php namespace Jc91715\Book\Components;

use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Jc91715\Book\Models\Chapter;

class Sections extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'sections Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [
            'translatePage' => [
                'title' => '翻译页',
                'type' => 'dropdown',
                'default' => 'translate'
            ]
        ];
    }
    public function getTranslatePageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }
    public function onRun()
    {
        $chapter_id = $this->param('chapter_id');
        $chapter = Chapter::find($chapter_id);
        if(!$chapter){
            abort(404);
        }

        $this->page['sections']=$chapter->sections;
        $this->page['translatePage']=$this->property('translatePage');
    }
}
