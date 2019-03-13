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