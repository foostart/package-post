@webiste: http://foostart.com

@package-name: sample
@author: Kang
@date: 27/12/2017
@version: 2.0

@features

1. CRUD
2. Add category to form
3. Language standard
4. Add filters on table data
5. Add token for prevent XSRF

php artisan vendor:publish --provider="Foostart\Post\PostServiceProvider" --force

php artisan vendor:publish --provider="Foostart\Slideshow\SlideshowServiceProvider" --force


Step 1: composer require unisharp/laravel-filemanager:~1.8


Step 2: Add service providers

 UniSharp\LaravelFilemanager\LaravelFilemanagerServiceProvider::class,
 Intervention\Image\ImageServiceProvider::class,

Step 3: And add class aliases

 'Image' => Intervention\Image\Facades\Image::class,

Step 4: Publish the package’s config and assets :

 php artisan vendor:publish --tag=lfm_config
 php artisan vendor:publish --tag=lfm_public

Step 5: Clear cache
 php artisan route:clear
 php artisan config:clear

Step 6:  php artisan storage:link

Step 7: Clear auth


lfm.php 
clear auth

Step 8: 

unisharp\laravel-filemanager\src\Handlers\ConfigHandler.php

<?php

namespace UniSharp\LaravelFilemanager\Handlers;

class ConfigHandler
{
    public function userField()
    {
        //original
        //return auth()->user()->id;
        $auth = \App::make('authenticator');
        $user = $auth->getLoggedUser();
        if (empty($user)) {
            return NULL;
        }
        return $user->id;
    }
}
