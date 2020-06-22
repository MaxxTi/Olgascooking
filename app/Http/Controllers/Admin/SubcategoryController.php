<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Category;
use App\Subcategory;
use App\Product;

class SubcategoryController extends Controller
{
    public function addSubcategory(Request $request, $category_id) {
    	$category = Category::findOrFail($category_id);
    	$subcategories = $category->subcategories;

    	$breadcrumb = [
            route('admin.admin.show_admin_page') => 'Административная панель',
            route('admin.category.show_categories') => 'Категории',
            route('admin.category.show_category', ['category_id' => $category->id]) => $category->name,
            'Добавление подкатегории',
        ];

    	if(!empty($request->subcategory_name)) {
        	$subcategory = new Subcategory;
            $subcategory->name = $request->subcategory_name;
            $subcategory->description = $request->subcategory_description;
            $subcategory->product_category_id = $category_id;
            $subcategory->save();

            return redirect(route('admin.category.show_category', ['category_id' => $category_id]));
        }

        return view('admin.subcategory.addSubcategory', ['category' => $category,'breadcrumb' => $breadcrumb]);
    }

    public function editSubcategory(Request $request, $subcategory_id) {
    	$subcategory = Subcategory::findOrFail($subcategory_id);
    	$category = $subcategory->category;

    	$breadcrumb = [
            route('admin.admin.show_admin_page') => 'Административная панель',
            route('admin.category.show_categories') => 'Категории',
            route('admin.category.show_category', ['category_id' => $category->id]) => $category->name,
            $subcategory->name,
        ];

        if(!empty($request->subcategory_name)) {
        	$subcategory->name = $request->subcategory_name;
        	$subcategory->description = $request->subcategory_description;
        	$subcategory->save();

        	return redirect(route('admin.category.show_category', ['category_id' => $category->id]));
        }

    	return view('admin.subcategory.editSubcategory', ['subcategory' => $subcategory, 'breadcrumb' => $breadcrumb]);
    }

    public function deleteSubcategory($subcategory_id) {
    	$subcategory = Subcategory::findOrFail($subcategory_id);
    	$category_id = $subcategory->category->id;

        $products = Product::where([
                ['cat_sub_type', 'subcategory'],
                ['cat_sub_id', $subcategory_id],
            ])->get();

        foreach($products as $product) {
            $product->delete();
        }

    	$subcategory->delete();

    	return redirect()->route('admin.category.show_category', ['category_id' => $category_id]);
    }

    public function showDeletedSubcategories($category_id) {
        $category = Category::findOrFail($category_id);
        $subcategories = Subcategory::onlyTrashed()
            ->where('product_category_id', $category_id)
            ->get();
            
        $breadcrumb = [
            route('admin.admin.show_admin_page') => 'Административная панель',
            route('admin.category.show_categories') => 'Категории',
            route('admin.category.show_category', ['category_id' => $category->id]) => $category->name,
            'Удаленные подкатегории',
        ];

        return view('admin.subcategory.showDeletedSubcategories', [
                'category' => $category,
                'subcategories' => $subcategories,
                'breadcrumb' => $breadcrumb,
            ]
        );
    }

    public function recoverSubcategory($subcategory_id) {
        Subcategory::onlyTrashed()
            ->where('id', $subcategory_id)
            ->restore();

        Product::onlyTrashed()
            ->where([
                ['cat_sub_type', '=', 'subcategory'],
                ['cat_sub_id', '=', $subcategory_id]
            ])
            ->restore();

        $category_id = Subcategory::findOrFail($subcategory_id)->category->id;

        return $this->showDeletedSubcategories($category_id);
    }
}
