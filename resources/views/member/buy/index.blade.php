@extends('layouts.app')

@section('header')
	<h1>Jual Barang Bekas</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Jual Barang Bekas</li>
	</ol>
@endsection

@section('content')
	@php
		$bg = ['aqua', 'red', 'blue', 'orange', 'green', 'grey', 'purple'];
	@endphp
	<div class="row">
		@foreach( $categories as $i => $category )
			<div class="col-lg-6 col-xs-12">
				<div class="small-box bg-{{ $bg[$i] }}">
					<div class="inner">
						<h3>{{ $category->name }}</h3>
						<p>
							Harga Jual <b style="font-size: 1.5em;">Rp. {{ number_format($category->price_buy) }}</b> / kg
						</p>
						<p>
							Minimal Jual <b style="font-size: 1.5em;">{{ $category->min_buy }}</b> kg
						</p>
					</div>
					<div class="icon">
						<i class="fa fa-cube"></i>
					</div>
					<a href="{{ route('member.buy.index') }}/{{ $category->id }}" class="small-box-footer">Jual <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
		@endforeach
	</div>
@endsection