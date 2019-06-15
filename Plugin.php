<?php namespace Jc91715\Book;

use Backend;
use System\Classes\PluginBase;
use System\Models\Revision as Revision;
use Jc91715\Book\Classes\Diff as Diff;
use RainLab\User\Models\User;
use Jc91715\Book\Models\Chapter;

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
//    public function registerFormWidgets()
//    {
//        return [
//            'Jc91715\Book\FormWidgets\RevisionHistory' => 'revisionHistory'
//        ];
//    }
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
        Revision::extend(function($model){
            /* Revison can access to the login user */
            $model->belongsTo['user'] = ['RainLab\User\Models\User'];

            /* Revision can use diff function */
            $model->addDynamicMethod('getDiff', function() use ($model){
                return Diff::toHTML(Diff::compare($model->old_value, $model->new_value));
            });
        });
        User::extend(function($model){
            $model->morphToMany ['chapters'] = [Chapter::class,'name'=>'userable'];
        });
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
            'Jc91715\Book\Components\Translate' => 'Translate',
            'Jc91715\Book\Components\Sections' => 'Sections',
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
