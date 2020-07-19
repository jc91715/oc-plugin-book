<?php namespace Jc91715\Book\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Videos Back-end Controller
 */
class Videos extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Jc91715.Book', 'book', 'videos');
    }
}
