@extends('layouts.app')

@section('title', $title)

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
					<td><a class="btn btn-default btn-sm" href="{{ route('admin.product.soft_delete_product', ['product_id' => $product->id]) }}" role="button">удалить</a></td>
				</tr>
			@endforeach

		</table>		

	</div>

	<hr>

	<a class="btn btn-outline-primary" href="{{ $link_to_add_product }}" role="button">Добавить продукт</a>
	<a class="btn btn-outline-primary" href="{{ $link_to_deleted_products }}" role="button">Удаленные продукты</a>

</div>

@endsection