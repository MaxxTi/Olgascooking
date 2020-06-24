@extends('layouts.app')

@section('title', $category->name)

@section('content')

<div class="container">

	@include('admin.elems.breadcrumb')

	<div class="row">
		<p>В этом разделе находятся удаленные подкатегории. Их можно полностью удалить или восстановить. В восстанвленной подкатегории будут продукты, которые были в подкатегории на момент удаления этой подкатегории. При полном удалении подкатегории будут удалены все данные о продуктах входящих в эту подкатегорию</p>
		<p>Список удаленных подкатегорий:</p>
	</div>

	<div class="row">
		<table class="col-md-6 table table-bordered">
				
			@foreach($subcategories as $subcategory)
				<tr>
					<td>{{ $subcategory->name }}</td>
					<td><a class="btn btn-default btn-sm" href="{{ route('admin.subcategory.recover_subcategory', ['subcategory_id' => $subcategory->id]) }}" role="button">восстановить</a></td>
					<td><a class="btn btn-default btn-sm" href="{{ route('admin.subcategory.force_delete', ['subcategory_id' => $subcategory->id]) }}" role="button">удалить</a></td>
				</tr>
			@endforeach

		</table>		
	</div>

	<hr>

</div>

@endsection