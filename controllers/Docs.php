<?php namespace Jc91715\Book\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Docs Back-end Controller
 */
class Docs extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend\Behaviors\RelationController',
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';
    public $relationConfig = 'config_relation.yaml';
    public $requiredPermissions = ['jc91715.book.doc'];
    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Jc91715.Book', 'book', 'docs');
    }
}
