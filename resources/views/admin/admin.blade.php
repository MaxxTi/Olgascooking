@extends('layouts.app')

@section('title', 'Административная панель')

@section('content')

<div class="container">
	<div class="row">
		
		<div class="col-xs-12 col-sm-6 col-lg-4">
			<h3>Раздел меню</h3>
			<ul class="list-group">
			  <li class="list-group-item"><a href="{{ route('admin.category.show_categories') }}">Категории</a></li>
			  <li class="list-group-item"><a href="{{ route('admin.product.add_product') }}">Добавить продукт</a></li>
			</ul>
		</div>

		<div class="col-xs-12 col-sm-6 col-lg-4">
			<h3>Раздел страницы</h3>
			<ul class="list-group">
			  <li class="list-group-item"><a href="">О нас</a></li>
			  <li class="list-group-item"><a href="">Контакты</a></li>
			  <li class="list-group-item"><a href="">Блог</a></li>
			  <li class="list-group-item"><a href="">Галерея</a></li>
			  <li class="list-group-item"><a href="">Кейтеринг</a></li>
			</ul>
		</div>

	</div>
</div>

@endsection