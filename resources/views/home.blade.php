@extends('layouts.app')


@section('links')
<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/home.css') }}">
@endsection


@section('content')

<div class="container-fluid container-md row row-custom pb-4 px-0">
	<div class="row p-0 col-12 col-lg-10 offset-lg-1 mx-auto mb-3">
		<div class="container-fluid col-12 mb-3">
			<div class="carousel-custom" id="carousel">
				<ul class="carousel-custom-switch">
					@if(count($articles->primary))
						@for($i = 0; $i < min([count($articles->primary), 5]); $i++)
						<li data-target="#customCarousel" class="carousel-custom-caption active" data-slide-to="{{ $i }}"></li>
						@endfor
					@endif
				</ul>

				<div class="carousel-custom-inner">
					@if(count($articles->primary))
						@for($i = 0; $i < min([count($articles->primary), 5]); $i++)
						<div class="carousel-custom-slide" style="background-image: url('{{ $articles->primary[$i]->coverPath() }}');">
							<div class="carousel-custom-content">
								<div class="carousel-custom-content-inner">
									<h3 class="carousel-custom-header">
										{{ $articles->primary[$i]->title }}
									</h3>
									<p class="carousel-custom-body">
										{!! \Illuminate\Support\Str::limit($articles->primary[$i]->body, 150, $end='...') !!} 
									</p>
									<a class="btn btn-dark carousel-custom-button" href="{{ route('articles.show', $articles->primary[$i]->slug) }}">
										Read More
									</a>
								</div>
							</div>
						</div>
						@endfor
					@endif
				</div>

				<a class="carousel-custom-control-prev" href="#carousel" role="button" data-slide="prev" style="z-index: 100;">
					<span class="carousel-custom-control-prev-icon fas fa-chevron-left" aria-hidden="true"></span>
					<span class="sr-only">Previous</span>
				</a>

				<a class="carousel-custom-control-next" href="#carousel" role="button" data-slide="next" style="z-index: 100;">
					<span class="carousel-custom-control-next-icon fas fa-chevron-right" aria-hidden="true"></span>

					<span class="sr-only">Next</span>
				</a>
			</div>
		</div>

		@if(count($articles->secondary))
			@for($i = 0; $i < 2; $i++)
			<div class="col-sm-6 my-3 d-flex flex-row align-items-stretch" style="content: border-box;">
				<div class="article-tile">
					<a href="{{ route('articles.show', $articles->secondary[$articleCategories[$i]->name]->slug) }}" class="article-tile-image-container">
						<div class="article-tile-image-big" style="background-image: url('{{ $articles->secondary[$articleCategories[$i]->name]->coverPath() }}');"></div>
					</a>
					<div class="article-tile-caption p-3">
						<strong class="d-inline-block mb-2 text-primary">
							<a href="{{ route('categories.show', $articleCategories[$i]->slug) }}">
								{{ ucfirst($articleCategories[$i]->name) }}
							</a>
						</strong>
						<h3 class="mb-0">
							{{ $articles->secondary[$articleCategories[$i]->name]->title }}
						</h3>
						<span class="mb-1 font-italic text-muted">
							{{ $articles->secondary[$articleCategories[$i]->name]->created_at->format('j F, Y') }}
						</span>
						<p class="card-text mb-auto">
							{!! \Illuminate\Support\Str::limit($articles->secondary[$articleCategories[$i]->name]->body, 180, $end='...') !!}
						</p>
						<a href="{{ route('articles.show', $articles->secondary[$articleCategories[$i]->name]->slug) }}">
							Continue reading
						</a>
					</div>
				</div>
			</div>
			@endfor
		@endif

		@if(count($articles->ternary))
			@for($i = 0; $i < min([count($articles->ternary), 6]); $i++)
			<div class="col-sm-6 col-lg-4 my-3" style="content: border-box;">
				<div class="article-tile h-100">
					<a href="{{ route('articles.show', $articles->ternary[$i]->slug) }}" class="article-tile-image-container">
						<div class="article-tile-image-big" style="background-image: url('{{ $articles->ternary[$i]->coverPath() }}');"></div>
					</a>
					<div class="article-tile-caption p-3">
						<h4 class="mb-0">
							{{ $articles->ternary[$i]->title }}
						</h4>
						<span class="mb-1 font-italic text-muted">
							{{ $articles->ternary[$i]->created_at->format('j F, Y') }}
						</span>
						<p class="card-text mb-auto">
							{!! \Illuminate\Support\Str::limit($articles->ternary[$i]->body, 180, $end='...') !!}
						</p>
						<a href="{{ route('articles.show', $articles->ternary[$i]->slug) }}">
							Continue reading
						</a>
					</div>
				</div>
			</div>
			@endfor
		@endif

		<div class="d-flex justify-content-center w-100">
			<a class="btn btn-lg btn-primary mt-4 d-flex btn-more-articles" href="{{ route('articles.index') }}">
				More Articles
			</a>
		</div>
	</div>
</div>
@endsection


@section('scripts')
<script type="text/javascript" src="{{ URL::asset('js/home.js') }}"></script>
@endsection