@extends('layouts.app')

@section('title', 'Добаление подкатегории')

@section('content')

<div class="container">
	<div class="row">

		@include('admin.elems.breadcrumb')
		
		@include('admin.elems.form-add-edit-subcat')

	</div>
</div>

@endsection