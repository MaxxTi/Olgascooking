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

Route::get('/', 'MainPageController@showPage')->name('mainPage.showPage');

Auth::routes(['verify' => true]);

Route::get('/logout', 'HomeController@logout')->name('home.logout');
Route::get('/home', 'HomeController@index')->name('home')->middleware('verified');

Route::group(['prefix' => 'admin',
			  'middleware' => ['auth', 'isAdmin']], function() {
	Route::get('/', 'AdminController@showAdminPage')->name('admin.page');

	// Добавить категорию
	Route::match(['get', 'post'], 'add-category', 'AdminController@addCategory')->name('admin.add_category');
	// Редактировать категорию
	Route::match(['get', 'post'], 'edit-category/{category_id}', 'AdminController@editCategory')->name('admin.edit_category');
	// Удалить категорию
	Route::get('delete-category/{id}', 'AdminController@deleteCategory')->name('admin.delete_category');
	// Все категории
	Route::get('categories', 'AdminController@showCategories')->name('admin.categories');

	// Добавление подкатегории
	Route::match(['get', 'post'], 'add-subcategory/{category_id}', 'AdminController@addSubcategory')->name('admin.add_subcategory');
	// Редактировать подкатегорию
	Route::match(['get', 'post'], 'edit-subcategory/{subcategory_id}', 'Admincontroller@editSubcategory')->name('admin.edit_subcategory');
	// Удаление подкатегории
	Route::get('delete-subcategory/{id}', 'AdminController@deleteSubcategory')->name('admin.delete_subcategory');
	// Все подкатегории в категории
	Route::get('subcategories/{category_id}', 'AdminController@showSubcategories')->name('admin.subcategories');

	// Продукты по подкатегориям
	Route::get('products/{subcategory_id}', 'AdminController@showSubcategoryProducts')->name('admin.subcategory.products');
});