@extends('layouts.app')

@section('title', 'Категории')

@section('content')

<div class="container">

	@include('admin.elems.breadcrumb')

	<div class="row">
		
		<p>В этом разделе можно восстановить или полнотью удалить категории вместе с подкатегориями или продуктами.</p>
		<p>Список категорий:</p>
		
		<table class="col-md-6 table table-bordered">
				
			@foreach($categories as $category)
				<tr>
					<td>{{ $category->name }}</td>
					<td><a class="btn btn-default btn-sm" href="{{ route('admin.category.recover_category', ['category_id' => $category->id]) }}" role="button">восстановить</a></td>
					<td><a class="btn btn-default btn-sm" href="" role="button">удалить</a></td>
				</tr>
			@endforeach

		</table>		

	</div>

	<hr>

</div>

@endsection