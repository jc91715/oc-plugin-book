<?php namespace Jc91715\Book\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Chapters Back-end Controller
 */
class Chapters extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend\Behaviors\RelationController',
        'Backend\Behaviors\ReorderController',
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $relationConfig = 'config_relation.yaml';
    public $reorderConfig = 'config_reorder.yaml';
    public $requiredPermissions = ['jc91715.book.chapter'];
    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Jc91715.Book', 'book', 'docs');
    }

    public function index()
    {
        $this->asExtension('ListController')->index();
    }
    public function reorder()
    {
        $params = $this->params;
        if(count($params)>0){
            $doc_id = (int)$params[0];
           $this->vars['doc_id']=$doc_id;
        }else{
            abort(404);
        }

        parent::reorder();
    }
//    public function listExtendQuery($query)
//    {
//        $params = $this->params;
//        if(count($params)>0){
//            $doc_id = (int)$params[0];
//            $query->where('doc_id', $doc_id);
//        }else{
//            abort(404);
//        }
//    }
    public function reorderExtendQuery($query)
    {
        $params = $this->params;
        if(count($params)>0){
            $doc_id = (int)$params[0];
            $query->where('doc_id', $doc_id);
        }else{
            abort(404);
        }
    }
}
