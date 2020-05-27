@extends('layouts.app')

@section('title', 'Добаление категории')

@section('content')

<div class="container">
	<div class="row">

		@include('admin.elems.breadcrumb')
		
		@include('admin.elems.form-add-edit-cat')

	</div>
</div>

@endsection