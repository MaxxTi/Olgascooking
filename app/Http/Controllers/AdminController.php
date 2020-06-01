<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Category;
use App\Subcategory;

class AdminController extends Controller
{
    public function showAdminPage() {

    	return view('admin.admin');
    }

    public function showCategories() {
    	$cats = Category::all();
        $breadcrumb = [
            route('admin.page') => 'Административная панель',
            route('admin.categories') => 'Категории'
        ];

    	return view('admin.adminCategories', ['categories' => $cats, 'breadcrumb' => $breadcrumb]);
    }

    public function addCategory(Request $request) {
    	$breadcrumb = [
            route('admin.page') => 'Административная панель',
            route('admin.categories') => 'Категории',
            'Добавление категории'
        ];

        if(!empty($request->category_name)) {
            $category = new Category;
            $category->name = $request->category_name;
            $category->save();

            return redirect(route('admin.categories'));
        }

    	return view('admin.addCategory', ['breadcrumb' => $breadcrumb]);
    }

    public function editCategory(Request $request, $category_id) {
    	$category = Category::findOrFail($category_id);

    	$breadcrumb = [
            route('admin.page') => 'Административная панель',
            route('admin.categories') => 'Категории',
            $category->name,
        ];

        if(!empty($request->category_name)) {
            $category->name = $request->category_name;
            $category->save();

            return redirect(route('admin.categories'));
        }

    	return view('admin.editCategory', ['category' => $category, 'breadcrumb' => $breadcrumb]);
    }

    public function deleteCategory($category_id) {
    	Category::findOrFail($category_id)->delete();
    	return redirect()->route('admin.categories');
    }

    public function showSubcategories($category_id) {
    	$category = Category::findOrFail($category_id);
    	$subcategories = $category->subcategories;

    	$breadcrumb = [
            route('admin.page') => 'Административная панель',
            route('admin.categories') => 'Категории',
            $category->name,
        ];

    	return view('admin.adminSubcategories', ['category' => $category, 'subcategories' => $subcategories, 'breadcrumb' => $breadcrumb]);
    }

    public function addSubcategory(Request $request, $category_id) {
    	$category = Category::findOrFail($category_id);
    	$subcategories = $category->subcategories;

    	$breadcrumb = [
            route('admin.page') => 'Административная панель',
            route('admin.categories') => 'Категории',
            route('admin.subcategories', ['category_id' => $category->id]) => $category->name,
            'Добавление подкатегории',
        ];

    	if(!empty($request->subcategory_name)) {
        	$subcategory = new Subcategory;
            $subcategory->name = $request->subcategory_name;
            $subcategory->description = $request->subcategory_description;
            $subcategory->product_category_id = $category_id;
            $subcategory->save();

            return redirect(route('admin.subcategories', ['category_id' => $category_id]));
        }

        return view('admin.addSubcategory', ['category' => $category,'breadcrumb' => $breadcrumb]);
    }

    public function editSubcategory(Request $request, $subcategory_id) {
    	$subcategory = Subcategory::findOrFail($subcategory_id);
    	$category = $subcategory->category;

    	$breadcrumb = [
            route('admin.page') => 'Административная панель',
            route('admin.categories') => 'Категории',
            route('admin.subcategories', ['category_id' => $category->id]) => $category->name,
            $subcategory->name,
        ];

        if(!empty($request->subcategory_name)) {
        	$subcategory->name = $request->subcategory_name;
        	$subcategory->description = $request->subcategory_description;
        	$subcategory->save();

        	return redirect(route('admin.subcategories', ['category_id' => $category->id]));
        }

    	return view('admin.editSubcategory', ['subcategory' => $subcategory, 'breadcrumb' => $breadcrumb]);
    }

    public function deleteSubcategory($subcategory_id) {
    	$subcategory = Subcategory::findOrFail($subcategory_id);
    	$category_id = $subcategory->category->id;
    	$subcategory->delete();

    	return redirect()->route('admin.subcategories', ['category_id' => $category_id]);
    }

    public function showSubcategoryProducts($subcategory_id) {
    	$subcategory = Subcategory::findOrFail($subcategory_id);
    	$category = $subcategory->category;
    	$products = [];

    	$breadcrumb = [
            route('admin.page') => 'Административная панель',
            route('admin.categories') => 'Категории',
            route('admin.subcategories', ['category_id' => $category->id]) => $category->name,
            $subcategory->name,
        ];

        return view('admin.adminProducts', ['products' => $products, 'subcategory' => $subcategory, 'breadcrumb' => $breadcrumb]);
    }
}
