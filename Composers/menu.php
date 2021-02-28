<?php

use LaravelAcl\Authentication\Classes\Menu\SentryMenuFactory;
use Foostart\Category\Helpers\FooCategory;
use Foostart\Category\Helpers\SortTable;

/*
|-----------------------------------------------------------------------
| GLOBAL VARIABLES
|-----------------------------------------------------------------------
|   $sidebar_items
|   $sorting
|   $order_by
|   $plang_admin = 'post-admin'
|   $plang_front = 'post-front'
*/
View::composer([
                'package-post::admin.post-edit',
                'package-post::admin.post-form',
                'package-post::admin.post-items',
                'package-post::admin.post-item',
                'package-post::admin.post-search',
                'package-post::admin.post-config',
                'package-post::admin.post-lang',
    ], function ($view) {

        //Order by params
        $params = Request::all();

        /**
         * $plang-admin
         * $plang-front
         */

        $plang_admin = 'post-admin';
        $plang_front = 'post-front';

        $fooCategory = new FooCategory();
        $key = $fooCategory->getContextKeyByRef('admin/posts');

        /**
         * $sidebar_items
         */
        $sidebar_items = [
            trans('post-admin.sidebar.add') => [
                'url' => URL::route('posts.edit', []),
                'icon' => '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>'
            ],
            trans('post-admin.sidebar.list') => [
                "url" => URL::route('posts.list', []),
                'icon' => '<i class="fa fa-list-ul" aria-hidden="true"></i>'
            ],
            trans('post-admin.sidebar.category') => [
                'url'  => URL::route('categories.list',['_key='.$key]),
                'icon' => '<i class="fa fa-sitemap" aria-hidden="true"></i>'
            ],
            trans('post-admin.sidebar.config') => [
                "url" => URL::route('posts.config', []),
                'icon' => '<i class="fa fa-braille" aria-hidden="true"></i>'
            ],
            trans('post-admin.sidebar.lang') => [
                "url" => URL::route('posts.lang', []),
                'icon' => '<i class="fa fa-language" aria-hidden="true"></i>'
            ],
        ];

        /**
         * $sorting
         * $order_by
         */
        $orders = [
            '' => trans($plang_admin.'.form.no-selected'),
            'id' => trans($plang_admin.'.fields.id'),
            'post_name' => trans($plang_admin.'.fields.name'),
            'status' => trans($plang_admin.'.columns.status'),
            'updated_at' => trans($plang_admin.'.fields.updated_at'),
        ];
        $sortTable = new SortTable();
        $sortTable->setOrders($orders);
        $sorting = $sortTable->linkOrders();



        //Order by
        $order_by = [
            'asc' => trans('category-admin.order.by-asc'),
            'desc' => trans('category-admin.order.by-des'),
        ];

        // assign to view
        $view->with('sidebar_items', $sidebar_items );
        $view->with('order_by', $order_by);
        $view->with('sorting', $sorting);
        $view->with('plang_admin', $plang_admin);
        $view->with('plang_front', $plang_front);
});
