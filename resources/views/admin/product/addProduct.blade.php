@extends('layouts.app')

@section('title', 'Добаление продукта')

@section('content')

<div class="container">
	<div class="row">

		@include('admin.elems.breadcrumb')

		@include('admin.elems.form-add-edit-product')

	</div>
</div>

@endsection