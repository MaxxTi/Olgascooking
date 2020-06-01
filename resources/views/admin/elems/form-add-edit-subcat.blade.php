<div class="col-xs-12 col-sm-6 col-lg-4">

	<form action="" method="POST">

		{{ csrf_field() }}

	  <div class="form-group">
	    <label for="subcategory_name">Название подкатегории</label>
	    <input type="text" class="form-control" id="subcategory_name" name="subcategory_name" placeholder="Название" 
			@if(isset($subcategory->name))
				value="{{ $subcategory->name }}"
			@endif
	    >
	  </div>

	  <div class="form-group">
	    <label for="subcategory_description">Описание подкатегории</label>
	    <input type="text" class="form-control" id="subcategory_description" name="subcategory_description" placeholder="Название" 
			@if(isset($subcategory->description))
				value="{{ $subcategory->description }}"
			@endif
	    >
	  </div>
	  
	  <button type="submit" class="btn btn-default">Добавить</button>

	</form>

</div>