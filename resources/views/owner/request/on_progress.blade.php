@extends('layouts.owner')

@section('header')
	<h1>Sedang Dalam Pengerjaan</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Sedang Dalam Pengerjaan</li>
	</ol>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header">
					<h3 class="box-title">
						Kerajinan yang sedang dalam proses
					</h3>
				</div>
				<div class="box-body">
					@foreach( $bid_requests as $bid )
						@if( $bid->request->status == \App\Models\Request::STATUS_ON_PROGRESS )
							<div class="row">
						        <div class="col-md-4">
						            <a href="blog-post.html">
						                <img class="img-responsive img-thumbnail" src="{{ asset('storage/'. $bid->request->file->path) }}" style="width: 100%; height:200px;">
						            </a>
						        </div>
						        <div class="col-md-8">
						            <div class="row">
						            	<div class="col-md-12">
						            		<div class="row">
						            			<div class="col-md-6">
						            				<p class="pull-left" style="font-size: 2em;">{{ $bid->request->title }}</p>
						            			</div>	
						            			<div class="col-md-6">
						            				<p class="pull-right"><i class="fa fa-clock-o"></i> Di Posting Pada {{ date_format($bid->request->created_at,"d F Y - H:i") }}</p>
						            				<p class="pull-right"><i class="fa fa-clock-o"></i> Mulai Pengerjaan {{ date_format($bid->created_at,"d F Y - H:i") }}</p>
						            			</div>
						            		</div>
						            	</div>
						            </div>
						            
						            <div class="row">
						            	<div class="col-md-12">
						            		<p>
								            	<i class="fa fa-user-o"></i>
								            	oleh : <a href="#">{{ $bid->request->user->name }}</a>
								            </p>
						            	</div>
						            </div>
						            
						            <p>
						            	{!! strlen($bid->request->description) > 250 ? substr($bid->request->description, 0, 250) . '...' : $bid->request->description !!}
						            </p>

						            <a class="btn btn-primary pull-right" href="{{ route('owner.request.detail', ['id' => $bid->request->id]) }}">
						            	Lihat Selengkapnya <i class="fa fa-angle-right"></i>
						            </a>
						        </div>
						    </div>
					    @endif
					@endforeach
				</div>
			</div>
		</div>
	</div>
@endsection