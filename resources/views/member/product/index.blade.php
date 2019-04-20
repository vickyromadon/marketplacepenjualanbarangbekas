@extends('layouts.app')

@section('header')
	<h1>
	Kategori {{ $category->name }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li>Kategori</li>
		<li class="active">{{ $category->name }}</li>
	</ol>
@endsection

@section('content')
    <div class="row">
        @foreach($products as $product)
        	<div class="col-md-4 img-portfolio">
	            <a href="portfolio-item.html">
	                <img class="img-responsive img-hover img-thumbnail" src="{{ asset('storage/'. $product->file->path) }}" style="width: 100%; height:250px;">
	            </a>

	            <h3>
	                <a href="#">{{ $product->name }}</a>
	            </h3>
	            
	            <p>
	            	{!! strlen($product->description) > 200 ? substr($product->description, 0, 200) . '...' : $product->description !!}
	            </p>

				<div class="row">
					<div class="col-md-6">
						<h3 class="pull-left" style="margin: 0px;">
                            <i class="fa fa-money"> Rp. {{ number_format($product->price) }}</i>
                        </h3>
                        
					</div>
					<div class="col-md-6">
						<a href="{{ route('member.product.show', ['id' => $product->id]) }}" class="btn btn-info pull-right">
                            <i class="fa fa-eye"></i> Lihat
                        </a>
					</div>
				</div>
	        </div>
        @endforeach
    </div>
    <div class="row text-center">
        <div class="col-lg-12">
            {{ $products->links() }}
        </div>
    </div>
@endsection