@extends('layouts.app')

@section('title', $title)

@section('content')

<div class="container">

	@include('admin.elems.breadcrumb')

	<div class="row">
		
		<p>В этом разделе удаленные продукты, их можно восстановить или полностью удалить. </p>
		<p>Список продуктов:</p>
		
		<table class="col-md-6 table table-bordered">
				
			@foreach($products as $product)
				<tr>
					<td>{{ $product->name }}</td>
					<td><a class="btn btn-default btn-sm" href="{{ route('admin.product.recover_product', ['product_id' => $product->id]) }}" role="button">восстановить</a></td>
					<td><a class="btn btn-default btn-sm" href="{{ route('admin.product.force_delete', ['product_id' => $product->id]) }}" role="button">удалить</a></td>
				</tr>
			@endforeach

		</table>		

	</div>

	<hr>
	
	<div class="row">
		<a class="btn btn-outline-primary" href="{{ route('admin.product.force_delete_all', ['p_id' => $products_id]) }}" role="button">Удалить все</a>
	</div>
</div>

@endsection