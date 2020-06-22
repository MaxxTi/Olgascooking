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

	Route::group(['namespace' => 'Admin'], function() {

		// Главная админки
		Route::get('/', 'AdminController@showAdminPage')->name('admin.admin.show_admin_page');

		// Добавить категорию
		Route::match(['get', 'post'], 'add-category', 'CategoryController@addCategory')->name('admin.category.add_category');
		// Редактировать категорию
		Route::match(['get', 'post'], 'edit-category/{category_id}', 'CategoryController@editCategory')->name('admin.category.edit_category');
		// Удалить категорию
		Route::get('delete-category/{id}', 'CategoryController@deleteCategory')->name('admin.category.delete_category');
		// Все категории
		Route::get('categories', 'CategoryController@showCategories')->name('admin.category.show_categories');
		// Категория (все подкатегории в категории)
		Route::get('category/{category_id}', 'CategoryController@showCategory')->name('admin.category.show_category');

			// Добавление подкатегории
		Route::match(['get', 'post'], 'add-subcategory/{category_id}', 'SubcategoryController@addSubcategory')->name('admin.subcategory.add_subcategory');
		// Редактировать подкатегорию
		Route::match(['get', 'post'], 'edit-subcategory/{subcategory_id}', 'Subcategorycontroller@editSubcategory')->name('admin.subcategory.edit_subcategory');
		// Удаление подкатегории
		Route::get('delete-subcategory/{id}', 'SubcategoryController@deleteSubcategory')->name('admin.subcategory.delete_subcategory');
		// Восстановление подкатегории
		Route::get('recover-subcategory/{id}', 'SubcategoryController@recoverSubcategory')->name('admin.subcategory.recover_subcategory');
		// Полностью удалить подкатегорию
		
		// удаленные подкатегории
		Route::get('deleted-subcategories/{category_id}', 'SubcategoryController@showDeletedSubcategories')->name('admin.subcategory.deleted-subcategories');


		// Продукты в категории
		Route::get('category/products/{category_id}', 'ProductController@showCategoryProducts')->name('admin.product.show_category_products');
		// Продукты в подкатегориям
		Route::get('subcategory/products/{subcategory_id}', 'ProductController@showSubcategoryProducts')->name('admin.product.show_subcategory_products');

		// Добавление продукта
		Route::group(['prefix' => 'add-product'], function(){
			// Добавление продукта в категорию (без подкатегории)
			Route::match(['get', 'post'], '{cat_subcat_id?}/category', 'ProductController@addProduct')->name('admin.product.add_product_category');
			// Добавление продукта в подкатегорию
			Route::match(['get', 'post'], '{cat_subcat_id?}/subcategory', 'ProductController@addProduct')->name('admin.product.add_product_subcategory');
			// Добавление продукта из админки
			Route::match(['get', 'post'], '', 'ProductController@addProduct')->name('admin.product.add_product');
		});

		// Удаление продукта
		Route::get('soft-delete-product/{product_id}', 'ProductController@softDeleteProduct')->name('admin.product.soft_delete_product');
		// Удаленные продукты
		Route::group(['prefix' => 'deleted-products'], function() {
			Route::get('{cat_subcat_id}/category', 'ProductController@showDeletedProducts')->name('admin.product.deleted_products_category');
			Route::get('{cat_subcat_id}/subcategory', 'ProductController@showDeletedProducts')->name('admin.product.deleted_products_subcategory');
		});
		// Восстановить продукт
		Route::get('recover-product/{product_id}', 'ProductController@recoverProduct')->name('admin.product.recover_product');
		// Полностью удалить продукт
		Route::get('force-delete/{product_id}', 'ProductController@forceDeleteProduct')->name('admin.product.force_delete');
		// Удалить все удаленные продукты в категории или подкатегории
		Route::get('force-delete-products', 'ProductController@forceDeleteProducts')->name('admin.product.force_delete_all');
	});


});