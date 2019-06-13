<?php namespace Jc91715\Book;

use Backend;
use System\Classes\PluginBase;

/**
 * book Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'book',
            'description' => 'No description provided yet...',
            'author'      => 'jc91715',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {

    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {

        return [
            'Jc91715\Book\Components\BookLists' => 'BookLists',
            'Jc91715\Book\Components\BookList' => 'BookList',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {


        return [
            'jc91715.book.doc' => [
                'tab' => 'book',
                'label' => 'doc'
            ],
            'jc91715.book.chapter' => [
                'tab' => 'book',
                'label' => 'chapter'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {


        return [
            'book' => [
                'label'       => 'book',
                'url'         => Backend::url('jc91715/book/docs'),
                'icon'        => 'icon-leaf',
                'permissions' => ['jc91715.book.doc'],
                'order'       => 500,
            ],
        ];
    }
}
