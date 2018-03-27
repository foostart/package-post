<?php namespace Foostart\Post\Controllers\Admin;

/*
|-----------------------------------------------------------------------
| PostAdminController
|-----------------------------------------------------------------------
| @author: Kang
| @website: http://foostart.com
| @date: 28/12/2017
|
*/


use Illuminate\Http\Request;
use URL, Route, Redirect;
use Illuminate\Support\Facades\App;

use Foostart\Category\Library\Controllers\FooController;
use Foostart\Post\Models\Post;
use Foostart\Category\Models\Category;
use Foostart\Post\Validators\PostValidator;


class PostAdminController extends FooController {

    public $obj_item = NULL;
    public $obj_category = NULL;

    public function __construct() {

        parent::__construct();
        // models
        $this->obj_item = new Post(array('perPage' => 10));
        $this->obj_category = new Category();

        // validators
        $this->obj_validator = new PostValidator();

        // set language files
        $this->plang_admin = 'post-admin';
        $this->plang_front = 'post-front';

        // package name
        $this->package_name = 'package-post';
        $this->package_base_name = 'post';

        // root routers
        $this->root_router = 'posts';

        // page views
        $this->page_views = [
            'admin' => [
                'items' => $this->package_name.'::admin.'.$this->package_base_name.'-items',
                'edit'  => $this->package_name.'::admin.'.$this->package_base_name.'-edit',
                'config'  => $this->package_name.'::admin.'.$this->package_base_name.'-config',
                'lang'  => $this->package_name.'::admin.'.$this->package_base_name.'-lang',
            ]
        ];

        $this->data_view['status'] = $this->obj_item->getPluckStatus();

        // //set category
        $this->category_ref_name = 'admin/posts';

    }

    /**
     * Show list of items
     * @return view list of items
     * @date 27/12/2017
     */
    public function index(Request $request) {

        $params = $request->all();

        $items = $this->obj_item->selectItems($params);

        // display view
        $this->data_view = array_merge($this->data_view, array(
            'items' => $items,
            'request' => $request,
            'params' => $params,
        ));

        return view($this->page_views['admin']['items'], $this->data_view);
    }

    /**
     * Edit existing item by {id} parameters OR
     * Add new item
     * @return view edit page
     * @date 26/12/2017
     */
    public function edit(Request $request) {

        $item = NULL;
        $categories = NULL;

        $params = $request->all();
        $params['id'] = $request->get('id', NULL);


        //get item data by id
        if (!empty($params['id'])) {

            $item = $this->obj_item->selectItem($params, FALSE);

            if (empty($item)) {
                return Redirect::route($this->root_router.'.list')
                                ->withMessage(trans($this->plang_admin.'.actions.edit-error'));
            }
        }

        //get categories by context
        $context = $this->obj_item->getContext($this->category_ref_name);
        if ($context) {
            $params['context_id'] = $context->context_id;
            $categories = $this->obj_category->pluckSelect($params);
        }

        // display view
        $this->data_view = array_merge($this->data_view, array(
            'item' => $item,
            'categories' => $categories,
            'request' => $request,
            'context' => $context,
        ));
        return view($this->page_views['admin']['edit'], $this->data_view);
    }

    /**
     * Processing data from POST method: add new item, edit existing item
     * @return view edit page
     * @date 27/12/2017
     */
    public function post(Request $request) {

        $item = NULL;

        $params = array_merge($request->all(), $this->getUser());

        $is_valid_request = $this->isValidRequest($request);

        $id = (int) $request->get('id');

        if ($is_valid_request && $this->obj_validator->validate($params)) {// valid data

            // update existing item
            if (!empty($id)) {

                $item = $this->obj_item->find($id);

                if (!empty($item)) {

                    $params['id'] = $id;
                    $item = $this->obj_item->updateItem($params);

                    // message
                    return Redirect::route($this->root_router.'.edit', ["id" => $item->id])
                                    ->withMessage(trans($this->plang_admin.'.actions.edit-ok'));
                } else {

                    // message
                    return Redirect::route($this->root_router.'.list')
                                    ->withMessage(trans($this->plang_admin.'.actions.edit-error'));
                }

            // add new item
            } else {

                $item = $this->obj_item->insertItem($params);

                if (!empty($item)) {

                    //message
                    return Redirect::route($this->root_router.'.edit', ["id" => $item->id])
                                    ->withMessage(trans($this->plang_admin.'.actions.add-ok'));
                } else {

                    //message
                    return Redirect::route($this->root_router.'.edit', ["id" => $item->id])
                                    ->withMessage(trans($this->plang_admin.'.actions.add-error'));
                }

            }

        } else { // invalid data

            $errors = $this->obj_validator->getErrors();

            // passing the id incase fails editing an already existing item
            return Redirect::route($this->root_router.'.edit', $id ? ["id" => $id]: [])
                    ->withInput()->withErrors($errors);
        }
    }

    /**
     * Delete existing item
     * @return view list of items
     * @date 27/12/2017
     */
    public function delete(Request $request) {

        $item = NULL;
        $flag = TRUE;
        $params = array_merge($request->all(), $this->getUser());
        $delete_type = isset($params['del-forever'])?'delete-forever':'delete-trash';
        $id = (int)$request->get('id');
        $ids = $request->get('ids');

        $is_valid_request = $this->isValidRequest($request);

        if ($is_valid_request && (!empty($id) || !empty($ids))) {

            $ids = !empty($id)?[$id]:$ids;

            foreach ($ids as $id) {

                $params['id'] = $id;

                if (!$this->obj_item->deleteItem($params, $delete_type)) {
                    $flag = FALSE;
                }
            }
            if ($flag) {
                return Redirect::route($this->root_router.'.list')
                                ->withMessage(trans($this->plang_admin.'.actions.delete-ok'));
            }
        }

        return Redirect::route($this->root_router.'.list')
                        ->withMessage(trans($this->plang_admin.'.actions.delete-error'));
    }

    /**
     * Manage configuration of package
     * @param Request $request
     * @return view config page
     */
    public function config(Request $request) {
        $is_valid_request = $this->isValidRequest($request);
        // display view
        $config_path = realpath(base_path('config/package-post.php'));
        $package_path = realpath(base_path('vendor/foostart/package-post'));

        $config_bakup = realpath($package_path.'/storage/backup/config');

        if ($version = $request->get('v')) {
            //load backup config
            $content = file_get_contents(base64_decode($version));
        } else {
            //load current config
            $content = file_get_contents($config_path);
        }

        if ($request->isMethod('post') && $is_valid_request) {

            //create backup of current config
            file_put_contents($config_bakup.'/package-post-'.date('YmdHis',time()).'.php', $content);

            //update new config
            $content = $request->get('content');

            file_put_contents($config_path, $content);
        }

        $backups = array_reverse(glob($config_bakup.'/*'));

        $this->data_view = array_merge($this->data_view, array(
            'request' => $request,
            'content' => $content,
            'backups' => $backups,
        ));

        return view($this->page_views['admin']['config'], $this->data_view);
    }


    /**
     * Manage languages of package
     * @param Request $request
     * @return view lang page
     */
    public function lang(Request $request) {
        $is_valid_request = $this->isValidRequest($request);
        // display view
        $langs = config('package-post.langs');
        $lang_paths = [];

        if (!empty($langs) && is_array($langs)) {
            foreach ($langs as $key => $value) {
                $lang_paths[$key] = realpath(base_path('resources/lang/'.$key.'/post-admin.php'));
            }
        }

        $package_path = realpath(base_path('vendor/foostart/package-post'));

        $lang_bakup = realpath($package_path.'/storage/backup/lang');
        $lang = $request->get('lang')?$request->get('lang'):'en';
        $lang_contents = [];

        if ($version = $request->get('v')) {
            //load backup lang
            $group_backups = base64_decode($version);
            $group_backups = empty($group_backups)?[]: explode(';', $group_backups);

            foreach ($group_backups as $group_backup) {
                $_backup = explode('=', $group_backup);
                $lang_contents[$_backup[0]] = file_get_contents($_backup[1]);
            }

        } else {
            //load current lang
            foreach ($lang_paths as $key => $lang_path) {
                $lang_contents[$key] = file_get_contents($lang_path);
            }
        }

        if ($request->isMethod('post') && $is_valid_request) {

            //create backup of current config
            foreach ($lang_paths as $key => $value) {
                $content = file_get_contents($value);

                //format file name post-admin-YmdHis.php
                file_put_contents($lang_bakup.'/'.$key.'/post-admin-'.date('YmdHis',time()).'.php', $content);
            }


            //update new lang
            foreach ($langs as $key => $value) {
                $content = $request->get($key);
                file_put_contents($lang_paths[$key], $content);
            }

        }

        //get list of backup langs
        $backups = [];
        foreach ($langs as $key => $value) {
            $backups[$key] = array_reverse(glob($lang_bakup.'/'.$key.'/*'));
        }

        $this->data_view = array_merge($this->data_view, array(
            'request' => $request,
            'backups' => $backups,
            'langs'   => $langs,
            'lang_contents' => $lang_contents,
            'lang' => $lang,
        ));

        return view($this->page_views['admin']['lang'], $this->data_view);
    }

    /**
     * Edit existing item by {id} parameters OR
     * Add new item
     * @return view edit page
     * @date 26/12/2017
     */
    public function copy(Request $request) {

        $params = $request->all();

        $item = NULL;
        $params['id'] = $request->get('cid', NULL);

        $context = $this->obj_item->getContext($this->category_ref_name);

        if (!empty($params['id'])) {

            $item = $this->obj_item->selectItem($params, FALSE);

            if (empty($item)) {
                return Redirect::route($this->root_router.'.list')
                                ->withMessage(trans($this->plang_admin.'.actions.edit-error'));
            }

            $item->id = NULL;
        }

        $categories = $this->obj_category->pluckSelect($params);

        // display view
        $this->data_view = array_merge($this->data_view, array(
            'item' => $item,
            'categories' => $categories,
            'request' => $request,
            'context' => $context,
        ));

        return view($this->page_views['admin']['edit'], $this->data_view);
    }


}