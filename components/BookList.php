<?php namespace Jc91715\Book\Components;

use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Jc91715\Book\Models\Doc;
use Jc91715\Book\Models\Chapter;

class BookList extends ComponentBase
{
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
    public function onRun()
    {
        $this->addCss('assets/css/doc.css');
        $this->addJs('assets/css/doc.js');
        $doc_id = $this->property('doc_id');
        $doc=Doc::find($doc_id);
        if(!$doc){
            $this->setStatusCode(404);
            return $this->controller->run('404');
        }

        $chapter_id = $this->property('chapter_id');
        $chapter =  $chapter = Chapter::where('doc_id',$doc_id)->find($chapter_id);

        $this->page['chapter']='';
        if($chapter){
            $this->page['chapter']=$chapter;
        }

        $chapters = $doc->chapters()->getNested();
        $this->page['chapters']=$chapters;
        $this->page['translatePage']=$this->property('translatePage');
        $this->page['sectionsPage']=$this->property('sectionsPage');//没有用到

    }
}
