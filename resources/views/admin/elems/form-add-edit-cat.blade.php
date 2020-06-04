<div class="col-xs-12 col-sm-6 col-lg-4">

	<form action="" method="POST">

		{{ csrf_field() }}

	  <div class="form-group">
	    <label for="category_name">Название категории</label>
	    <input type="text" class="form-control" id="category_name" name="category_name" placeholder="Название" 
			@if(isset($category->name))
				value="{{ $category->name }}"
			@endif
	    >
	  </div>

	  <div class="form-group">
		<label for="only_products">В категории будут только продукты</label>
		<input type="checkbox" class="form-check" name="only_products" id="only_productsonly_products" 
		@if(isset($category))
			@if($category->only_products == 1)
				checked
			@endif
		@endif
		>
	  </div>
	  
	  <button type="submit" class="btn btn-default">Добавить</button>

	</form>

</div>