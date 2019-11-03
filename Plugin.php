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
        \Event::listen('offline.sitesearch.query', function ($query) {

            // Search your plugin's contents
            $items = \Jc91715\Book\Models\Chapter::where('title', 'like', "%${query}%")
                ->orWhere('content_html', 'like', "%${query}%")
                ->get();

            // Now build a results array
            $results = $items->map(function ($item) use ($query) {

                // If the query is found in the title, set a relevance of 2
                $relevance = mb_stripos($item->title, $query) !== false ? 2 : 1;

                // Optional: Add an age penalty to older results. This makes sure that
                // newer results are listed first.
                // if ($relevance > 1 && $item->published_at) {
                //     $relevance -= $this->getAgePenalty($item->published_at->diffInDays(Carbon::now()));
                // }

                return [
                    'title'     => $item->title,
                    'text'      => $item->content_html,
                    'url'       => '/docs/'.$item->doc_id.'/chapters/' . $item->id,
                    'thumb'     => null, // Instance of System\Models\File
                    'relevance' => $relevance, // higher relevance results in a higher
                    // position in the results listing
                    // 'meta' => 'data',       // optional, any other information you want
                    // to associate with this result
                    // 'model' => $item,       // optional, pass along the original model
                ];
            });




            return [
                'provider' => '--来自中文文档', // The badge to display for this result
                'results'  => $results,
            ];
        });
        \Event::listen('offline.sitesearch.query', function ($query) {

            // Search your plugin's contents
            $items = \Jc91715\Book\Models\Section::orWhere('content_html', 'like', "%${query}%")
                ->get();

            // Now build a results array
            $results = $items->map(function ($item) use ($query) {

                // If the query is found in the title, set a relevance of 2
                $relevance = mb_stripos($item->title, $query) !== false ? 2 : 1;

                // Optional: Add an age penalty to older results. This makes sure that
                // newer results are listed first.
                // if ($relevance > 1 && $item->published_at) {
                //     $relevance -= $this->getAgePenalty($item->published_at->diffInDays(Carbon::now()));
                // }

                return [
                    'title'     => $item->title,
                    'text'      => $item->content_html,
                    'url'       => '/docs/'.$item->doc_id.'/chapters/' . $item->chapter_id,
                    'thumb'     => null ,// Instance of System\Models\File
                    'relevance' => $relevance, // higher relevance results in a higher
                    // position in the results listing
                    // 'meta' => 'data',       // optional, any other information you want
                    // to associate with this result
                    // 'model' => $item,       // optional, pass along the original model
                ];
            });




            return [
                'provider' => '--来自中文文档', // The badge to display for this result
                'results'  => $results,
            ];
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

                'sideMenu' => [
                    'docs' => [
                        'label'       => '文档列表',
                        'icon'        => 'icon-list-ul',
                        'url'         => Backend::url('jc91715/book/docs'),
                        'permissions' => ['jc91715.book.*']
                    ],
                    'chapters' => [
                        'label'       => '章节列表',
                        'icon'        => 'icon-list-ul',
                        'url'         => Backend::url('jc91715/book/chapters'),
                        'permissions' => ['jc91715.book.*']
                    ],
                    'sections' => [
                        'label'       => '分块列表',
                        'icon'        => 'icon-list-ul',
                        'url'         => Backend::url('jc91715/book/sections'),
                        'permissions' => ['jc91715.book.*']
                    ]
                ]
            ],
        ];
    }
}
