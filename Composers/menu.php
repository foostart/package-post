<?php

use LaravelAcl\Authentication\Classes\Menu\SentryMenuFactory;


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

        /**
         * $plang-admin
         * $plang-front
         */
        $plang_admin = 'post-admin';
        $plang_front = 'post-front';

        $view->with('plang_admin', $plang_admin);
        $view->with('plang_front', $plang_front);

        /**
         * $sidebar_items
         */
        $view->with('sidebar_items', [
            trans('post-admin.sidebar.add') => [
                'url' => URL::route('posts.edit', []),
                'icon' => '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>'
            ],
            trans('post-admin.sidebar.list') => [
                "url" => URL::route('posts.list', []),
                'icon' => '<i class="fa fa-list-ul" aria-hidden="true"></i>'
            ],
            trans('post-admin.sidebar.config') => [
                "url" => URL::route('posts.config', []),
                'icon' => '<i class="fa fa-braille" aria-hidden="true"></i>'
            ],
            trans('post-admin.sidebar.lang') => [
                "url" => URL::route('posts.lang', []),
                'icon' => '<i class="fa fa-language" aria-hidden="true"></i>'
            ],
        ]);

        /**
         * $sorting
         * $order_by
         */
        $orders = [
            '' => trans($plang_admin.'.form.no-selected'),
            'id' => trans($plang_admin.'.fields.id'),
            'post_name' => trans($plang_admin.'.fields.name'),
            'updated_at' => trans($plang_admin.'.fields.updated_at'),
        ];
        $sorting = [
            'label' => $orders,
            'items' => [],
            'url' => []
        ];
        //Order by params
        $params = Request::all();

        $order_by = explode(',', @$params['order_by']);
        $ordering = explode(',', @$params['ordering']);
        foreach ($orders as $key => $value) {
            $_order_by = $order_by;
            $_ordering = $ordering;
            if (!empty($key)) {
                //existing key in order
                if (in_array($key, $order_by)) {
                    $index = array_search($key, $order_by);
                    switch ($_ordering[$index]) {
                        case 'asc':
                            $sorting['items'][$key] = 'asc';
                            $_ordering[$index] = 'desc';
                            break;
                        case 'desc':
                             $sorting['items'][$key] = 'desc';
                            $_ordering[$index] = 'asc';
                            break;
                        default:
                            break;
                    }
                    $order_by_str = implode(',', $_order_by);
                    $ordering_str = implode(',', $_ordering);

                } else {//new key in order
                    $sorting['items'][$key] = 'none';//asc
                    if (empty($params['order_by'])) {
                        $order_by_str = $key;
                        $ordering_str = 'asc';
                    } else {
                        $_order_by[] = $key;
                        $_ordering[] = 'asc';
                        $order_by_str = implode(',', $_order_by);
                        $ordering_str = implode(',', $_ordering);
                    }
                }
                $sorting['url'][$key]['order_by'] = $order_by_str;
                $sorting['url'][$key]['ordering'] = $ordering_str;
            }
        }
        foreach ($sorting['url'] as $key => $item) {
            $params['order_by'] = $item['order_by'];
            $params['ordering'] = $item['ordering'];
            $sorting['url'][$key] = Request::url().'?'.http_build_query($params);
        }
        $view->with('sorting', $sorting);

        //Order by
        $order_by = [
            'asc' => trans('foostart.order_by.asc'),
            'desc' => trans('foostart.order_by.desc'),
        ];
        $view->with('order_by', $order_by);
});
