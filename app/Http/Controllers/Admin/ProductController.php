<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\SoftDeletes;
use Validator;
use Illuminate\Support\Facades\Storage;
use App\Category;
use App\Subcategory;
use App\Product;

class ProductController extends Controller
{
    public function showCategoryProducts($category_id) {
    	$category = Category::findOrFail($category_id);
    	$products = $category->products;

    	$breadcrumb = [
            route('admin.admin.show_admin_page') => 'Административная панель',
            route('admin.category.show_categories') => 'Категории',
            $category->name,
        ];

        return view('admin.product.products', [
        	'products' => $products,
        	'breadcrumb' => $breadcrumb,
        	'title' => $category->name,
        	'link_to_add_product' => route('admin.product.add_product_category', ['category_id' => $category->id]),
        	'link_to_deleted_products' => route('admin.product.deleted_products_category', ['category_id' => $category_id]),
        ]);
    }

    public function showSubcategoryProducts($subcategory_id) {
    	$subcategory = Subcategory::findOrFail($subcategory_id);
    	$category = $subcategory->category;
    	$products = $subcategory->products;

    	$breadcrumb = [
            route('admin.admin.show_admin_page') => 'Административная панель',
            route('admin.category.show_categories') => 'Категории',
            route('admin.category.show_category', ['category_id' => $category->id]) => $category->name,
            $subcategory->name,
        ];

        return view('admin.product.products', [
        	'products' => $products,
        	'subcategory' => $subcategory,
        	'breadcrumb' => $breadcrumb,
        	'title' => $subcategory->name,
        	'link_to_add_product' => route('admin.product.add_product_subcategory', ['subcategory_id' => $subcategory->id]),
        	'link_to_deleted_products' => route('admin.product.deleted_products_subcategory', ['subcategory_id' => $subcategory_id]),
        ]);
    }

    public function addProduct(Request $request, $cat_subcat_id = null) {
    	$from = $request->url();

    	// если добавление из категории (продукт без подкатегории)
    	if(preg_match_all('#.+add-product/(\S+)/category$#', $from, $matches)) {
    		$category_id = $matches[1][0];
    		return $this->addProductCategory($request, $cat_subcat_id, $from);
    	}
    	// если добавление из подкатегории
    	if(preg_match_all('#.+add-product/(\S+)/subcategory$#', $from, $matches)) {
    		$subcategory_id = $matches[1][0];
    		return $this->addProductSubcategory($request, $cat_subcat_id, $from);
    	}
    	// если добавление продукта из админки
    	if(preg_match('#.+add-product$#', $from)) {
    		return 'from admin';
    	}
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
                return redirect()->to(route('admin.product.add_product_category', ['category_id' => $category_id]))
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

            return redirect(route('admin.product.show_category_products', ['category_id' => $request->cat_or_subcat_id]));
        }

    	$category = Category::findOrFail($category_id);

		$breadcrumb = [
            route('admin.admin.show_admin_page') => 'Административная панель',
            route('admin.category.show_categories') => 'Категории',
            route('admin.category.show_category', ['category_id' => $category->id]) => $category->name,
            'Новый продукт',
        ];

        return view('admin.product.addProduct', ['cat_or_subcat' => $category, 'breadcrumb' => $breadcrumb, 'action' => $action]);  	
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
                return redirect()
                ->to(route('admin.product.add_product_subcategory', ['subcategory_id' => $subcategory_id]))
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

            return redirect(route('admin.product.show_subcategory_products', ['subcategory_id' => $request->cat_or_subcat_id]));
        }

    	$subcategory = Subcategory::findOrFail($subcategory_id);
    	$category = $subcategory->category;

		$breadcrumb = [
            route('admin.admin.show_admin_page') => 'Административная панель',
            route('admin.category.show_categories') => 'Категории',
            route('admin.category.show_category', ['category_id' => $category->id]) => $category->name,
            route('admin.product.show_subcategory_products', ['subcategory_id' => $subcategory->id]) => $subcategory->name,
            'Новый продукт',
        ];

        return view('admin.product.addProduct', ['cat_or_subcat' => $subcategory, 'action' => $action, 'breadcrumb' => $breadcrumb]);  	
    }

    public function softDeleteProduct(Request $request, $product_id) {
    	$product = Product::findOrFail($product_id);
		$product->delete();

    	if($product->cat_sub_type == 'category') {
    		return redirect()->to(route('admin.product.show_category_products', ['category_id' => $product->cat_sub_id]));
    	}
    	
    	if($product->cat_sub_type == 'subcategory') {
    		return redirect()->to(route('admin.product.show_subcategory_products', ['subcategory_id' => $product->cat_sub_id]));
    	}
    }

    public function showDeletedProducts(Request $request, $cat_subcat_id) {
    	$from = $request->url();

    	// если удаленные из категории (продукт без подкатегории)
    	if(preg_match_all('#.+deleted-products/(\S+)/category$#', $from, $matches)) {
    		$category_id = $matches[1][0];
    		return $this->deletedProductsCategory($cat_subcat_id, $from);
    	}
    	// если удаленные из подкатегории
    	if(preg_match_all('#.+deleted-products/(\S+)/subcategory$#', $from, $matches)) {
    		$subcategory_id = $matches[1][0];
    		return $this->deletedProductsSubcategory($cat_subcat_id, $from);
    	}
    }
    private function deletedProductsCategory($category_id) {
    	$category = Category::findOrFail($category_id);
    	$products = Product::onlyTrashed()
    		->where([
    			['cat_sub_type', '=', 'category'],
    			['cat_sub_id', '=', $category_id]
    		])
    		->get();
        $products_id = [];

        foreach($products as $key=>$product) {
            $products_id[$key] = $product->id;
        }

    	$breadcrumb = [
            route('admin.admin.show_admin_page') => 'Административная панель',
            route('admin.category.show_categories') => 'Категории',
            route('admin.category.show_category', ['category_id' => $category->id]) => $category->name,
            'Удаленные продукты',
        ];

        return view('admin.product.deletedProducts', [
        	'products' => $products,
            'products_id' => $products_id,
        	'breadcrumb' => $breadcrumb,
        	'title' => $category->name,
        ]);
    }
    private function deletedProductsSubcategory($subcategory_id) {
    	$subcategory = Subcategory::findOrFail($subcategory_id);
    	$category = $subcategory->category;
    	$products = Product::onlyTrashed()
    		->where([
    			['cat_sub_type', '=', 'subcategory'],
    			['cat_sub_id', '=', $subcategory_id]
    		])
    		->get();

        $products_id = [];

        foreach($products as $key=>$product) {
            $products_id[$key] = $product->id;
        }

    	$breadcrumb = [
            route('admin.admin.show_admin_page') => 'Административная панель',
            route('admin.category.show_categories') => 'Категории',
            route('admin.category.show_category', ['category_id' => $category->id]) => $category->name,
            route('admin.product.show_subcategory_products', ['subcategory_id' => $subcategory->id]) => $subcategory->name,
            'Удаленные продукты',
        ];

        return view('admin.product.deletedProducts', [
        	'products' => $products,
            'products_id' => $products_id,
        	'breadcrumb' => $breadcrumb,
        	'title' => $subcategory->name,
        ]);
    }

    public function recoverProduct(Request $request, $product_id) {
    	Product::onlyTrashed()
    		->where('id', $product_id)
    		->restore();

		$product = Product::findOrFail($product_id);
		
    	if($product->cat_sub_type == 'category') {
    		$back = route('admin.product.deleted_products_category', [
    			'category_id' => $product->cat_sub_id,
    		]);
    	}

    	if($product->cat_sub_type == 'subcategory') {
    		$back = route('admin.product.deleted_products_subcategory', [
    			'subcategory_id' => $product->cat_sub_id,
    		]);
    	}

    	return redirect()->to($back);
    }

    public function forceDeleteProduct(Request $request, $product_id) {
    	$product = Product::onlyTrashed()
    		->where('id', $product_id)
    		->restore();

    	$product = Product::findOrFail($product_id);

    	if($product->cat_sub_type == 'category') {
    		$back = route('admin.product.deleted_products_category', [
    			'category_id' => $product->cat_sub_id,
    		]);
    	}

    	if($product->cat_sub_type == 'subcategory') {
    		$back = route('admin.product.deleted_products_subcategory', [
    			'subcategory_id' => $product->cat_sub_id,
    		]);
    	}

    	// удаляем картинку если она не дефолтная
        if($product->image_path !='storage/productImages/default.jpeg') {
            preg_match_all('#(productImages/.+)$#', $product->image_path, $matches);
            $image_path = $matches[1][0];
            Storage::disk('public')->delete($image_path);
        }

    	$product->forceDelete();

    	return redirect()->to($back);
    }

    public function forceDeleteProducts(Request $request) {
        $products_id = $request->input('p_id');
        Product::onlyTrashed()
            ->whereIn('id', $products_id)
            ->restore();

        $products = [];

        foreach($products_id as $id) {
            $products[] = Product::findOrFail($id);
        }


        $product_cat_sub_type = $products[0]->cat_sub_type;

        if($product_cat_sub_type == 'category') {
            $back = route('admin.product.deleted_products_category', [
                'category_id' => $products[0]->cat_sub_id,
            ]);
        }

        if($product_cat_sub_type == 'subcategory') {
            $back = route('admin.product.deleted_products_subcategory', [
                'subcategory_id' => $products[0]->cat_sub_id,
            ]);
        }

        foreach($products as $product) {
            // удаляем картинку если она не дефолтная
            if($product->image_path !='storage/productImages/default.jpeg') {
                preg_match_all('#(productImages/.+)$#', $product->image_path, $matches);
                $image_path = $matches[1][0];
                Storage::disk('public')->delete($image_path);
            }

            $product->forceDelete();    
        }

        return redirect()->to($back);
    }
     
}
