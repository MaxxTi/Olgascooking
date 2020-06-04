<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\User;
use App\Category;
use App\Subcategory;
use App\Product;

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

            if($request->only_products == null) {
            	$category->only_products = 0;
            } else {
            	$category->only_products = 1;
            }

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

            if($request->only_products == null) {
            	$category->only_products = 0;
            } else {
            	$category->only_products = 1;
            }

            $category->save();

            return redirect(route('admin.categories'));
        }

    	return view('admin.editCategory', ['category' => $category, 'breadcrumb' => $breadcrumb]);
    }

    public function deleteCategory($category_id) {
    	Category::findOrFail($category_id)->delete();
    	return redirect()->route('admin.categories');
    }

    public function showCategory($category_id) {
    	$category = Category::findOrFail($category_id);
    	$subcategories = $category->subcategories;

    	$breadcrumb = [
            route('admin.page') => 'Административная панель',
            route('admin.categories') => 'Категории',
            $category->name,
        ];

        // Если  категории только продукт
        if($category->only_products == 1) {
        	$products = $category->products;
        	return view('admin.adminProducts', ['products' => $products, 'breadcrumb' => $breadcrumb]);
        }

    	return view('admin.adminSubcategories', ['category' => $category, 'subcategories' => $subcategories, 'breadcrumb' => $breadcrumb]);
    }

    public function addSubcategory(Request $request, $category_id) {
    	$category = Category::findOrFail($category_id);
    	$subcategories = $category->subcategories;

    	$breadcrumb = [
            route('admin.page') => 'Административная панель',
            route('admin.categories') => 'Категории',
            route('admin.category', ['category_id' => $category->id]) => $category->name,
            'Добавление подкатегории',
        ];

    	if(!empty($request->subcategory_name)) {
        	$subcategory = new Subcategory;
            $subcategory->name = $request->subcategory_name;
            $subcategory->description = $request->subcategory_description;
            $subcategory->product_category_id = $category_id;
            $subcategory->save();

            return redirect(route('admin.category', ['category_id' => $category_id]));
        }

        return view('admin.addSubcategory', ['category' => $category,'breadcrumb' => $breadcrumb]);
    }

    public function editSubcategory(Request $request, $subcategory_id) {
    	$subcategory = Subcategory::findOrFail($subcategory_id);
    	$category = $subcategory->category;

    	$breadcrumb = [
            route('admin.page') => 'Административная панель',
            route('admin.categories') => 'Категории',
            route('admin.category', ['category_id' => $category->id]) => $category->name,
            $subcategory->name,
        ];

        if(!empty($request->subcategory_name)) {
        	$subcategory->name = $request->subcategory_name;
        	$subcategory->description = $request->subcategory_description;
        	$subcategory->save();

        	return redirect(route('admin.category', ['category_id' => $category->id]));
        }

    	return view('admin.editSubcategory', ['subcategory' => $subcategory, 'breadcrumb' => $breadcrumb]);
    }

    public function deleteSubcategory($subcategory_id) {
    	$subcategory = Subcategory::findOrFail($subcategory_id);
    	$category_id = $subcategory->category->id;
    	$subcategory->delete();

    	return redirect()->route('admin.category', ['category_id' => $category_id]);
    }

    public function showCategoryProducts($category_id) {
    	$category = Category::findOrFail($category_id);
    	$products = $category->products;

    	$breadcrumb = [
            route('admin.page') => 'Административная панель',
            route('admin.categories') => 'Категории',
            $category->name,
        ];

        return view('admin.adminProducts', ['products' => $products, 'breadcrumb' => $breadcrumb, 'title' => $category->name, 'link_to_add_product' => route('admin.addProductCategory', ['category_id' => $category->id])]);
    }

    public function showSubcategoryProducts($subcategory_id) {
    	$subcategory = Subcategory::findOrFail($subcategory_id);
    	$category = $subcategory->category;
    	$products = $subcategory->products;

    	$breadcrumb = [
            route('admin.page') => 'Административная панель',
            route('admin.categories') => 'Категории',
            route('admin.category', ['category_id' => $category->id]) => $category->name,
            $subcategory->name,
        ];

        return view('admin.adminProducts', ['products' => $products, 'subcategory' => $subcategory, 'breadcrumb' => $breadcrumb, 'title' => $subcategory->name, 'link_to_add_product' => route('admin.addProductSubcategory', ['subcategory_id' => $subcategory->id])]);
    }

    public function addProduct(Request $request, $cat_subcat_id = null) {
    	$from = $request->url();

    	// если добавление из подкатегории
    	if(preg_match_all('#.+add-product/(\S+)/subcategory$#', $from, $matches)) {
    		$subcategory_id = $matches[1][0];
    		return $this->addProductSubcategory($request, $cat_subcat_id, $from);
    	}
    	// если добавление из категории (продукт без подкатегории)
    	if(preg_match_all('#.+add-product/(\S+)/category$#', $from, $matches)) {
    		$category_id = $matches[1][0];
    		return $this->addProductCategory($request, $cat_subcat_id, $from);
    	}
    	// если добавление продукта из админки
    	if(preg_match('#.+add-product$#', $from)) {
    		return 'from admin';
    	}
    }
    private function addProductSubcategory($request, $subcategory_id, $action) {

    	if($request->isMethod('post')) {
   
            $rules = [
                'name' => 'required',
                'shortDesc' => 'required',
                'desc' => 'required',
                'price' => 'required|numeric',
            ];
            $messages = [
                'name.required' => 'Название продукта должно быть заполнено',
                'shortDesc.required' => 'Короткое описание продукта должно быть заполнено',
                'desc.required' => 'Описание продукта должно быть заполнено',
                'price.required' => 'Укажите цену продукта',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);

            if($validator->fails()) {
                return redirect('admin/add_product/' . $subcat_id)
                  ->withErrors($validator)
                  ->withInput();
            }

            $path = '/storage/productImages/default.jpeg';

            if(!empty($request->file('image'))) {
                $img = $request->file('image')->store('public/productImages');
                $path = Storage::url($img);
            }

            $product = new Product;
            $product->name = $request->name;
            $product->short_description = $request->shortDesc;
            $product->description = $request->desc;
            $product->price = $request->price;
            $product->cat_sub_id = $request->cat_or_subcat_id;
            $product->cat_sub_type = 'subcategory';
            $product->image_path = $path;
            $product->save();

            return redirect(route('admin.subcategory.products', ['subcategory_id' => $request->cat_or_subcat_id]));
        }

    	$subcategory = Subcategory::findOrFail($subcategory_id);
    	$category = $subcategory->category;

		$breadcrumb = [
            route('admin.page') => 'Административная панель',
            route('admin.categories') => 'Категории',
            route('admin.category', ['category_id' => $category->id]) => $category->name,
            route('admin.subcategory.products', ['subcategory_id' => $subcategory->id]) => $subcategory->name,
            'Новый продукт',
        ];

        return view('admin.addProduct', ['cat_or_subcat' => $subcategory, 'action' => $action, 'breadcrumb' => $breadcrumb]);  	
    }
    private function addProductCategory($request, $category_id, $action) {

    	if($request->isMethod('post')) {
   
            $rules = [
                'name' => 'required',
                'shortDesc' => 'required',
                'desc' => 'required',
                'price' => 'required|numeric',
            ];
            $messages = [
                'name.required' => 'Название продукта должно быть заполнено',
                'shortDesc.required' => 'Короткое описание продукта должно быть заполнено',
                'desc.required' => 'Описание продукта должно быть заполнено',
                'price.required' => 'Укажите цену продукта',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);

            if($validator->fails()) {
                return redirect('admin/add_product/' . $subcat_id)
                  ->withErrors($validator)
                  ->withInput();
            }

            $path = '/storage/productImages/default.jpeg';

            if(!empty($request->file('image'))) {
                $img = $request->file('image')->store('public/productImages');
                $path = Storage::url($img);
            }

            $product = new Product;
            $product->name = $request->name;
            $product->short_description = $request->shortDesc;
            $product->description = $request->desc;
            $product->price = $request->price;
            $product->cat_sub_id = $request->cat_or_subcat_id;
            $product->cat_sub_type = 'category';
            $product->image_path = $path;
            $product->save();

            return redirect(route('admin.category.products', ['category_id' => $request->cat_or_subcat_id]));
        }

    	$category = Category::findOrFail($category_id);

		$breadcrumb = [
            route('admin.page') => 'Административная панель',
            route('admin.categories') => 'Категории',
            route('admin.category', ['category_id' => $category->id]) => $category->name,
            'Новый продукт',
        ];

        return view('admin.addProduct', ['cat_or_subcat' => $category, 'breadcrumb' => $breadcrumb, 'action' => $action]);  	
    }
}
