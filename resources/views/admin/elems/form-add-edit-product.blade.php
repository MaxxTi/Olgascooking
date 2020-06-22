<div class="col-xs-12 col-sm-6 col-lg-4">

	<form action="{{ $action }}" method="POST" enctype="multipart/form-data">

		{{ csrf_field() }}

		<input type="hidden" name="cat_or_subcat_id" value="{{ $cat_or_subcat->id }}">

    <div class="form-group">
      <label for="name">Название продукта:</label>
      @if ($errors->has('name'))
          <div class="alert alert-danger" role="alert">
              {{ $errors->first('name') }}
          </div>
      @endif
      <input type="text" class="form-control" id="name" name="name" placeholder="Название продукта" value="{{ old('name') }}">
    </div>

    <div class="form-group">
      <label for="shortDesc">Короткое описание</label>
      @if ($errors->has('shortDesc'))
          <div class="alert alert-danger" role="alert">
              {{ $errors->first('shortDesc') }}
          </div>
      @endif
      <textarea class="form-control" id="shortDesc" name="shortDesc" rows="3">{{ old('shortDesc') }}</textarea>
    </div>

    <div class="form-group">
      <label for="desc">Описание продукта</label>
      @if ($errors->has('desc'))
          <div class="alert alert-danger" role="alert">
              {{ $errors->first('desc') }}
          </div>
      @endif
      <textarea class="form-control" id="desc" name="desc" rows="3">{{ old('desc') }}</textarea>
    </div>

    <div class="form-group">
      <label for="price">Цена</label>
      @if ($errors->has('price'))
          <div class="alert alert-danger" role="alert">
              {{ $errors->first('price') }}
          </div>
      @endif
      <input type="number" class="form-control" id="price" name="price" value="{{ old('price') }}">
    </div>

    <div class="form-group">
      <label for="image">Фото продукта</label>
      <input type="file" id="image" name="image">
      <p class="help-block">Будет отображено в основной карточке.</p>
    </div>

    <button type="submit" class="btn btn-default">Добавить продукт</button>

	</form>

</div>