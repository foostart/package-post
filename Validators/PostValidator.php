<?php namespace Foostart\Post\Validators;

use Foostart\Category\Library\Validators\FooValidator;
use Event;
use \LaravelAcl\Library\Validators\AbstractValidator;
use Foostart\Post\Models\Post;

use Illuminate\Support\MessageBag as MessageBag;

class PostValidator extends FooValidator
{

    protected $obj_post;

    public function __construct()
    {
        // add rules
        self::$rules = [
            'post_name' => ["required"],
            'post_overview' => ["required"],
            'post_description' => ["required"],
        ];

        // set configs
        self::$configs = $this->loadConfigs();

        // model
        $this->obj_post = new Post();

        // language
        $this->lang_front = 'post-front';
        $this->lang_admin = 'post-admin';

        // event listening
        Event::listen('validating', function($input)
        {
            self::$messages = [
                'post_name.required'          => trans($this->lang_admin.'.errors.required', ['attribute' => trans($this->lang_admin.'.fields.name')]),
                'post_overview.required'      => trans($this->lang_admin.'.errors.required', ['attribute' => trans($this->lang_admin.'.fields.overview')]),
                'post_description.required'   => trans($this->lang_admin.'.errors.required', ['attribute' => trans($this->lang_admin.'.fields.description')]),
            ];
        });


    }

    /**
     *
     * @param ARRAY $input is form data
     * @return type
     */
    public function validate($input) {

        $flag = parent::validate($input);
        $this->errors = $this->errors ? $this->errors : new MessageBag();

        //Check length
        $_ln = self::$configs['length'];

        $params = [
            'name' => [
                'key' => 'post_name',
                'label' => trans($this->lang_admin.'.fields.name'),
                'min' => $_ln['post_name']['min'],
                'max' => $_ln['post_name']['max'],
            ],
            'overview' => [
                'key' => 'post_overview',
                'label' => trans($this->lang_admin.'.fields.overview'),
                'min' => $_ln['post_overview']['min'],
                'max' => $_ln['post_overview']['max'],
            ],
            'description' => [
                'key' => 'post_description',
                'label' => trans($this->lang_admin.'.fields.description'),
                'min' => $_ln['post_description']['min'],
                'max' => $_ln['post_description']['max'],
            ],
        ];

        $flag = $this->isValidLength($input['post_name'], $params['name']) ? $flag : FALSE;
        $flag = $this->isValidLength($input['post_overview'], $params['overview']) ? $flag : FALSE;
        $flag = $this->isValidLength($input['post_description'], $params['description']) ? $flag : FALSE;

        return $flag;
    }


    /**
     * Load configuration
     * @return ARRAY $configs list of configurations
     */
    public function loadConfigs(){

        $configs = config('package-post');
        return $configs;
    }

}