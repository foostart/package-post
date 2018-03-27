<?php

use Illuminate\Session\TokenMismatchException;

/**
 * FRONT
 */
Route::get('post', [
    'as' => 'post',
    'uses' => 'Foostart\Post\Controllers\Front\PostFrontController@index'
]);


/**
 * ADMINISTRATOR
 */
Route::group(['middleware' => ['web']], function () {

    Route::group(['middleware' => ['admin_logged', 'can_see', 'in_context'],
                  'namespace' => 'Foostart\Post\Controllers\Admin',
        ], function () {

        /*
          |-----------------------------------------------------------------------
          | Manage post
          |-----------------------------------------------------------------------
          | 1. List of posts
          | 2. Edit post
          | 3. Delete post
          | 4. Add new post
          | 5. Manage configurations
          | 6. Manage languages
          |
        */

        /**
         * list
         */
        Route::get('admin/posts', [
            'as' => 'posts.list',
            'uses' => 'PostAdminController@index'
        ]);

        /**
         * edit-add
         */
        Route::get('admin/posts/edit', [
            'as' => 'posts.edit',
            'uses' => 'PostAdminController@edit'
        ]);

        /**
         * copy
         */
        Route::get('admin/posts/copy', [
            'as' => 'posts.copy',
            'uses' => 'PostAdminController@copy'
        ]);

        /**
         * post
         */
        Route::post('admin/posts/edit', [
            'as' => 'posts.post',
            'uses' => 'PostAdminController@post'
        ]);

        /**
         * delete
         */
        Route::get('admin/posts/delete', [
            'as' => 'posts.delete',
            'uses' => 'PostAdminController@delete'
        ]);

        /**
         * trash
         */
         Route::get('admin/posts/trash', [
            'as' => 'posts.trash',
            'uses' => 'PostAdminController@trash'
        ]);

        /**
         * configs
        */
        Route::get('admin/posts/config', [
            'as' => 'posts.config',
            'uses' => 'PostAdminController@config'
        ]);

        Route::post('admin/posts/config', [
            'as' => 'posts.config',
            'uses' => 'PostAdminController@config'
        ]);

        /**
         * language
        */
        Route::get('admin/posts/lang', [
            'as' => 'posts.lang',
            'uses' => 'PostAdminController@lang'
        ]);

        Route::post('admin/posts/lang', [
            'as' => 'posts.lang',
            'uses' => 'PostAdminController@lang'
        ]);

    });
});
