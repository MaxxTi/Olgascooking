@extends('layouts.app')

@section('title', $subcategory->name)

@section('content')

<div class="container">

	@include('admin.elems.breadcrumb')

	<div class="row">
		
		<p>В этом разделе можно добавить, переименовать или удалить продукты. </p>
		<p>Список продуктов:</p>
		
		<table class="col-md-6 table table-bordered">
				
			@foreach($products as $product)
				<tr>
					<td>{{ $product->name }}</td>
					<td><a class="btn btn-default btn-sm" href="" role="button">изменить</a></td>
					<td><a class="btn btn-default btn-sm" href="" role="button">удалить</a></td>
				</tr>
			@endforeach

		</table>		
		

	</div>

	<hr>
	<a class="btn btn-outline-primary" href="" role="button">Добавить продукт</a>

</div>

@endsection