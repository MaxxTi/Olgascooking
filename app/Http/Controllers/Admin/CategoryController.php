<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Category;
use App\Subcategory;
use App\Product;

class CategoryController extends Controller
{
    public function showCategories() {
    	$cats = Category::all();
        $breadcrumb = [
            route('admin.admin.show_admin_page') => 'Административная панель',
            route('admin.category.show_categories') => 'Категории'
        ];

    	return view('admin.category.showCategories', ['categories' => $cats, 'breadcrumb' => $breadcrumb]);
    }

    public function showCategory($category_id) {
    	$category = Category::findOrFail($category_id);
    	$subcategories = $category->subcategories;

    	$breadcrumb = [
            route('admin.admin.show_admin_page') => 'Административная панель',
            route('admin.category.show_categories') => 'Категории',
            $category->name,
        ];

        // Если в категории только продукты
        if($category->only_products == 1) {
        	
            return redirect()->to(route('admin.product.show_category_products', ['category_id' => $category_id]));
        }

    	return view('admin.category.showCategory', ['category' => $category, 'subcategories' => $subcategories, 'breadcrumb' => $breadcrumb]);
    }

    public function addCategory(Request $request) {
    	$breadcrumb = [
            route('admin.admin.show_admin_page') => 'Административная панель',
            route('admin.category.show_categories') => 'Категории',
            'Добавление категории'
        ];

        if(!empty($request->category_name)) {
            $category = new Category;
            $category->name = $request->category_name;

            if($request->only_products == null) {
            	$category->only_products = 0;
            } else {
            	$category->only_products = 1;
            }

            $category->save();

            return redirect(route('admin.category.show_categories'));
        }

    	return view('admin.category.addCategory', ['breadcrumb' => $breadcrumb]);
    }

    public function editCategory(Request $request, $category_id) {
    	$category = Category::findOrFail($category_id);

    	$breadcrumb = [
            route('admin.admin.show_admin_page') => 'Административная панель',
            route('admin.category.show_categories') => 'Категории',
            $category->name,
        ];

        if(!empty($request->category_name)) {
            $category->name = $request->category_name;

            if($request->only_products == null) {
            	$category->only_products = 0;
            } else {
            	$category->only_products = 1;
            }

            $category->save();

            return redirect(route('admin.category.show_categories'));
        }

    	return view('admin.category.editCategory', ['category' => $category, 'breadcrumb' => $breadcrumb]);
    }

    public function deleteCategory($category_id) {
        $category = Category::find($category_id);
        $subcategories = $category->subcategories;
        $products = Product::where([
            ['cat_sub_type', 'category'],
            ['cat_sub_id', $category_id]
        ])->get();

        if(!empty($products)) {
            foreach($products as $product) {
                $product->delete();
            }
        }

        if(!empty($subcategories)) {

            foreach($subcategories as $subcategory) {
                $products = Product::where([
                    ['cat_sub_type', 'subcategory'],
                    ['cat_sub_id', $subcategory->id]
                ])->get();

                foreach($products as $product) {
                    $product->delete();
                }

                $subcategory->delete();
            }

        }

        $category->delete();

    	return redirect()->route('admin.category.show_categories');
    }

    public function showDeletedCategories() {
        $categories = Category::onlyTrashed()->get();

        $breadcrumb = [
            route('admin.admin.show_admin_page') => 'Административная панель',
            route('admin.category.show_categories') => 'Категории',
            'Удаленные категории',
        ];

        return view('admin.category.showDeletedCategories', ['categories' => $categories,'breadcrumb' => $breadcrumb]);
    }

    public function recoverCategory($category_id) {
        Category::onlyTrashed()
            ->where('id', $category_id)
            ->restore();

        $category = Category::findOrFail($category_id);

        if($category->only_products == 1) {
            $products = Product::onlyTrashed()
                ->where([
                    ['cat_sub_type', 'category'],
                    ['cat_sub_id', $category_id]
                ])->get();

            foreach($products as $product) {
                $product->restore();
            }

        }

        if($category->only_products == 0) {
            $subcategories = Subcategory::onlyTrashed()
                ->where('product_category_id', $category_id)
                ->get();
            foreach($subcategories as $subcategory) {
                $subcategory->restore();
            }
        }

        return redirect()->to(route('admin.category.deleted_categories'));
    }
}
