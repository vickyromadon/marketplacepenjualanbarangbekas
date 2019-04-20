@extends('layouts.admin')

@section('header')
	<h1>Detail Produk</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li>Produk</li>
		<li class="active">Detail Produk</li>
	</ol>
@endsection

@section('content')
	<div class="box box-primary">
		<div class="box-header">
			<a href="{{ route('admin.product.index') }}" class="btn btn-warning"><i class="fa fa-reply"></i> Kembali</a>
		</div>
        <div class="box-body">
        	<div class="row">
	            <div class="col-md-4">
	                <img src="{{ asset('storage/'.$data->file->path) }}" class="img-responsive img-thumbnail" style="height: 300px; width: 100%;">
	            </div>
	            <div class="col-md-8">
	            	<div class="row">
	            		<div class="col-md-5">
							<strong>
								<i class="fa fa-chevron-circle-right margin-r-5"></i> 
								Nama Produk
							</strong>
							<p class="text-muted">
								{{ $data->name }}
							</p>

							<strong>
								<i class="fa fa-chevron-circle-right margin-r-5"></i> 
								Kuantitas
							</strong>
							<p class="text-muted">
								{{ $data->quantity }}
							</p>

							<strong>
								<i class="fa fa-chevron-circle-right margin-r-5"></i> 
								Harga
							</strong>
							<p class="text-muted">
								Rp. {{ $data->price }}
							</p>

						</div>
						
						<div class="col-md-5">
							<strong>
								<i class="fa fa-chevron-circle-right margin-r-5"></i> 
								Total Melihat
							</strong>
							<p class="text-muted">
								{{ $data->view }}
							</p>

							<strong>
								<i class="fa fa-chevron-circle-right margin-r-5"></i> 
								Kategori
							</strong>
							<p class="text-muted">
								{{ $data->category->name }}
							</p>

							<strong>
								<i class="fa fa-chevron-circle-right margin-r-5"></i> 
								Status
							</strong>
							<p class="text-muted">
								@if ( $data->status == \App\Models\Product::STATUS_PUBLISH )
									<span class="label label-success">{{ $data->status }}</span>
								@elseif( $data->status == \App\Models\Product::STATUS_UNPUBLISH )
									<span class="label label-warning">{{ $data->status }}</span>
								@else
									<span class="label label-danger">{{ $data->status }}</span>
								@endif
							</p>
						</div>
	            	</div>
	            	<div class="row">
	            		<div class="col-md-12">
	            			<strong>
								<i class="fa fa-chevron-circle-right margin-r-5"></i> 
								Deskripsi
							</strong>
							<p class="text-muted">
								{!! $data->description !!}
							</p>
	            		</div>
	            	</div>
	            </div>
            </div>
        </div>
        @if ( $data->status == \App\Models\Product::STATUS_BLOCKIR )
        	<div class="box-footer">
				<div class="col-md-12">
					<strong>Catatan : </strong> {{ $data->note }}
				</div>
	        </div>
        @endif
    </div>
@endsection