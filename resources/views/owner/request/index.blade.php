@extends('layouts.owner')

@section('header')
	<h1>Daftar Permintaan</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Daftar Permintaan</li>
	</ol>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="nav-tabs-custom">
				<ul class="nav nav-tabs">
					@foreach( $categories as $i => $category )
						<li class="{{ $i == 0 ? 'active' : '' }}">
							<a href="#{{ $category->name }}" data-toggle="tab"><b>{{ $category->name }}</b></a>
						</li>
					@endforeach
				</ul>
				<div class="tab-content">
					@foreach( $categories as $i => $category )
						<div class="{{ $i == 0 ? 'active' : '' }} tab-pane" id="{{ $category->name }}">
							@foreach( $requests as $request )
								@if( $category->name == $request->category->name )
									<div class="row">
								        <div class="col-md-4">
								            <a href="blog-post.html">
								                <img class="img-responsive img-thumbnail" src="{{ asset('storage/'. $request->file->path) }}" style="width: 100%; height:200px;">
								            </a>
								        </div>
								        <div class="col-md-8">
								            <div class="row">
								            	<div class="col-md-12">
								            		<p class="pull-left" style="font-size: 2em;">{{ $request->title }}</p>
								            		<p class="pull-right"><i class="fa fa-clock-o"></i> Di Posting Pada {{ date_format($request->created_at,"d F Y - H:i") }}</p>
								            	</div>
								            </div>
								            
								            <div class="row">
								            	<div class="col-md-12">
								            		<p>
										            	<i class="fa fa-user-o"></i>
										            	oleh : <a href="#">{{ $request->user->name }}</a>
										            </p>
								            	</div>
								            </div>
								            
								            <p>
								            	{!! strlen($request->description) > 250 ? substr($request->description, 0, 250) . '...' : $request->description !!}
								            </p>

								            <a class="btn btn-primary pull-right" href="{{ route('owner.request.index') }}/{{ $request->id }}">
								            	Lihat Selengkapnya <i class="fa fa-angle-right"></i>
								            </a>
								        </div>
								    </div>
								    <hr>
							    @endif
						    @endforeach
						</div>
					@endforeach
				</div>
			</div>
		</div>
	</div>
@endsection