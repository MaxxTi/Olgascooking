<div class="container">	
	<div class="row">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb">
				@foreach($breadcrumb as $key=>$elem)
					@if($loop->last)
						<li class="breadcrumb-item active" aria-current="page">{{ $elem }}</li>
					@else
						<li class="breadcrumb-item"><a href="{{ $key }}">{{ $elem }}</a></li>
					@endif
				@endforeach
			</ol>
		</nav>
	</div>
</div>