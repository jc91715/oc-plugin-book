<?php namespace Jc91715\Book\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Jc91715\Book\Models\Chapter;
/**
 * Chapters Back-end Controller
 */
class Chapters extends Controller
{
    public $showTree=true;
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend\Behaviors\RelationController',
        'Backend\Behaviors\ReorderController',
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = [

        'chapters' => 'config_list.yaml'
    ];
//    public $listConfig = 'config_list.yaml';
    public $relationConfig = 'config_relation.yaml';
    public $reorderConfig = 'config_reorder.yaml';
    public $requiredPermissions = ['jc91715.book.chapter'];
    public function __construct()
    {

        parent::__construct();

//        if(request()->input('scopeName')=='showTree'){
//            $this->showTrue =request()->input('value');
//            unset(request()['scopeName']);
//        }
        BackendMenu::setContext('Jc91715.Book', 'book', 'chapters');
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
    public function listFilterExtendScopes($scope)
    {

        if($scope->config->alias=='chaptersFilter'){
            $scopes = $scope->getScopes();
            $treeScope = $scopes['showTree'];
            if(request()->input('scopeName')=='showTree'){
                $scope->setScopeValue('showTree',request()->input('value'));
            }

            if($widget=$this->listGetWidget('chapters')){

                if( $treeScope->value=='true'){

                    $widget->showTree = true;
                    $widget->showSorting = true;
                    $widget->showPagination = false;
                }else{

                    $widget->showTree = false;
                    $widget->showSorting = false;
                    $widget->showPagination = false;

                }


            }
        }




    }
    public function listExtendColumns($widget)
    {

//        $widget->showTree = false;
//        $widget->showSorting = false;
//        $widget->showPagination = false;
//
//
//        $widget->showTree = true;
//        $widget->showSorting = true;
//        $widget->showPagination = false;


    }

}
