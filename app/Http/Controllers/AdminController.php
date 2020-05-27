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
}
