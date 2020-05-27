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
					<td><a class="btn btn-default btn-sm" href="category/{{ $category->id }}" role="button">подкатегория</a></td>
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