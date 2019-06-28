<?php namespace Jc91715\Book\Components;

use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Jc91715\Book\Models\Doc;

class BookLists extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'bookLists Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [

            'chapterPage' => [
                'title' => '章节页',
                'type' => 'dropdown',
                'default' => ''
            ]
        ];
    }
    public function getChapterPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }
    public function onRun()
    {
        $docs = Doc::oldest('id')->get();
        $this->page['docs'] = $docs;
        $this->page['chapterPage']=$this->property('chapterPage');
    }
}
