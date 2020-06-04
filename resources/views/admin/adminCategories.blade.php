@extends('layouts.app')

@section('title', 'Категории')

@section('content')

<div class="container">

	@include('admin.elems.breadcrumb')

	<div class="row">
		
		<p>В этом разделе можно добавить, переименовать или удалить категории.</p>
		<p>Список категорий:</p>
		
		<table class="col-md-6 table table-bordered">
				
			@foreach($categories as $category)
				<tr>
					<td>{{ $category->name }}</td>
					<td><a class="btn btn-default btn-sm" href="{{ route('admin.category', ['category_id' => $category->id]) }}" role="button">

					@if($category->only_products == 1)
						<a class="btn btn-default btn-sm" href="{{ route('admin.category.products', ['category_id' => $category->id]) }}" role="button">
						продукты</a>
					@else
						<a class="btn btn-default btn-sm" href="{{ route('admin.category', ['category_id' => $category->id]) }}" role="button">
						подкатегории</a>
					@endif
					
					</td>
					<td><a class="btn btn-default btn-sm" href="{{ route('admin.edit_category', ['id' => $category->id]) }}" role="button">изменить</a></td>
					<td><a class="btn btn-default btn-sm" href="{{ route('admin.delete_category', ['id' => $category->id]) }}" role="button">удалить</a></td>
				</tr>
			@endforeach

		</table>		
		

	</div>

	<hr>
	<a class="btn btn-outline-primary" href="{{ route('admin.add_category') }}" role="button">Добавить категорию</a>

</div>

@endsection