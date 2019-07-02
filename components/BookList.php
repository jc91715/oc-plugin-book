<?php namespace Jc91715\Book\Components;

use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Jc91715\Book\Models\Doc;
use Jc91715\Book\Models\Chapter;

class BookList extends ComponentBase
{

    public $doc;
    public function componentDetails()
    {
        return [
            'name'        => 'bookList Component',
            'description' => 'No description provided yet...'
        ];
    }

    public function defineProperties()
    {
        return [
            'doc_id' => [
                'title'       => '文档id',
                'description' => '',
                'default'     => '{{ :doc_id }}'
            ],
            'chapter_id' => [
                'title'       => '章节id',
                'description' => '',
                'default'     => '{{ :chapter_id }}',
            ],
            'translatePage' => [
                'title' => '翻译页',
                'type' => 'dropdown',
                'default' => 'translate'
            ],
            'sectionsPage' => [
                'title' => '分块页',
                'type' => 'dropdown',
                'default' => 'sections'
            ]
        ];
    }
    public function getTranslatePageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }
    public function getSectionsPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function init()
    {
        $doc_id = $this->property('doc_id');
        $this->doc = Doc::findOrFail($doc_id);
    }
    public function onRun()
    {
        $this->addCss('assets/css/doc.css');
        $this->addJs('assets/css/doc.js');
        $this->page['translatePage']=$this->property('translatePage');
//        $this->page['sectionsPage']=$this->property('sectionsPage');//没有用到

    }

    public function chapter()
    {
        $chapter_id = $this->property('chapter_id');

        return $this->doc->findChapter($chapter_id);
    }

    public function chapters()
    {
        return $this->doc->chapters()->getNested();
    }
}
