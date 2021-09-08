<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['middleware' => ['activity']], function () {

    // Homepage Route
    Route::get('/', 'Asep\BlogController@index')->name('home');

    // Authors Routes
    Route::get('/authors', 'Asep\BlogController@authors')->name('authors');
    Route::get('/author/{author}', 'Asep\BlogController@author')->name('author');

    // Contact Routes
    Route::get('/contact', 'Asep\ContactController@index')->name('contact');
    Route::post('/contact', 'Asep\ContactController@contactSend')->name('contactSend');




    // RSS Feed Route
    Route::feeds();

    // Register, Login, and forget PW Routes
    Auth::routes();
});

// Super Admin only routes
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'permission:perms.super.admin', 'activity']], function () {
    //
});

// Writer and above routes
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'permission:perms.writer', 'activity']], function () {
    Route::resource('posts', 'Asep\Admin\PostController', [
        'names'    => [
            'create'    => 'posts.create',
            'index'     => 'admin.posts',
            'update'    => 'updatepost',
            'store'     => 'storepost',
            'edit'      => 'editpost',
            'destroy'   => 'destroypost',
        ],
        'except' => [
            'show',
        ],
        'parameters' => [
            'post' => 'id',
        ],
    ]);

    Route::resource('tags', 'Asep\Admin\TagController', [
        'names'    => [
            'create'    => 'createtag',
            'index'     => 'showtags',
            'update'    => 'updatetag',
            'store'     => 'storetag',
            'edit'      => 'edittag',
            'destroy'   => 'destroytag',
        ],
        'except' => [
            'show',
        ],
        'parameters' => [
            'tag' => 'id',
        ],
    ]);

    Route::get('/uploads', 'Asep\Admin\AdminController@uploads')->name('admin-uploads');

    Route::resource('themes', 'Asep\Admin\ThemesManagementController', [
        'names' => [
            'index'     => 'themes',
            'create'    => 'createtheme',
            'update'    => 'updatetheme',
            'store'     => 'storetheme',
            'edit'      => 'edittheme',
            'destroy'   => 'destroytheme',
            'show'      => 'showtheme',
        ],
    ]);
    Route::post('/update-blog-theme', 'Asep\Admin\ThemesManagementController@updateDefaultTheme')->name('update-blog-theme');
});

// User and above routes
Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'permission:perms.user', 'activity']], function () {
    Route::get('/', 'Asep\Admin\AdminController@index')->name('admin');
    Route::get('/sitemap', 'Asep\Admin\AdminController@sitemap')->name('sitemap-admin');
    Route::post('/generate-sitemap', 'Asep\Admin\AdminController@generateSitemap')->name('generate-sitemap');
});

Route::group(['middleware' => ['activity']], function () {
    // Dynamic Pages Routes
    Route::get('/{slug}/', 'Asep\BlogController@showPost');
});
