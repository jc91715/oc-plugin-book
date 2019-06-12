<?php namespace Jc91715\Book\Components;

use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use Jc91715\Book\Models\Doc;
use Jc91715\Book\Models\Chapter;
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
        $this->page['content_html']='';
        if($chapter){
            $this->page['content_html']=$chapter->content_html;
        }
//        dd($chapter->content_html);
        $chapters = $doc->chapters()->getNested();
        $this->page['chapters']=$chapters;
        $this->page['chapterPage']=$this->property('chapterPage');


    }
}
