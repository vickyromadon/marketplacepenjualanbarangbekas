@extends('layouts.app')

@section('header')
	<h1>
	Detail Permintaan
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li>Permintaan</li>
		<li class="active">Detail Permintaan</li>
	</ol>
@endsection

@section('content')
	<div class="row">
		<div class="col-lg-12">
			<div class="box box-danger">
				<div class="box-header with-border">
					<h3 class="box-title pull-left">
						{{ $request->title }}
					</h3>

                    <p class="pull-right">
                        @if( $request->status == \App\Models\Request::STATUS_WAITING )
                            <span class="label label-danger">Belum Dikerjakan</span>
                        @elseif( $request->status == \App\Models\Request::STATUS_ON_PROGRESS )
                            <span class="label label-warning">Sedang Dikerjakan</span>
                        @else
                            <span class="label label-success">Selesai Dikerjakan</span>
                        @endif
                    </p>
				</div>
				<div class="box-body">
					<p class="pull-left">
						<i class="fa fa-user-o"></i> 
						Di Posting Oleh {{ $request->user->name }}
					</p>
					<p class="pull-right">
						<i class="fa fa-clock-o"></i> 
						Di Posting Pada {{ date_format($request->created_at,"d F y - H:i") }}
					</p>
						
					<img class="img-responsive img-thumbnail" src="{{ asset('storage/'. $request->file->path) }}" style="width: 100%; height: 500px;">
					
					<hr>
					
					<p>
						{!! $request->description !!}
					</p>	
				</div>
			</div>
		</div>
	</div>

    <div class="row">
        @if( $request->status == \App\Models\Request::STATUS_WAITING )
            <div class="col-lg-12">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">Penawaran Pengrajin</h3>
                    </div>
                    <div class="box-body">
                        <ul class="products-list product-list-in-box">
                            @foreach($request->bid_requests as $bid)
                                <li class="item">
                                    <div class="product-img">
                                        @if( $bid->user->file == null )
                                            <img src="{{ asset('images/avatar_owner.png') }}">
                                        @else
                                            <img src="{{ asset('storage/'. $bid->user->file->path) }}">
                                        @endif
                                    </div>
                                    <div class="product-info">
                                        <a href="#" class="product-title"> 
                                            {{ $bid->user->name }}
                                        </a>
                                        <span class="label label-warning pull-right">
                                            Rp. {{ number_format($bid->price) }}
                                        </span>
                                        <span class="product-description">
                                            Waktu Pengerjaan {{ $bid->processing_time }} Hari
                                        </span>
                                        <p>
                                            {{ $bid->description }}
                                        </p>
                                    </div>
                                </li>
                            @endforeach     
                        </ul>
                    </div>
                    <div class="box-footer">
                        @if( $request->bidder > 0 )
                            <button id="btnBid" class="btn btn-success pull-right">
                                Pilih Pengrajin
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @else
            <div class="col-md-12">
                <div class="box box-danger">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            Dikerjakan oleh pengrajin
                        </h3>
                    </div>
                    <div class="box-body">
                        <ul class="products-list product-list-in-box">
                            <li class="item">
                                <div class="product-img">
                                    @if( $request->bid_request->user->file == null )
                                        <img src="{{ asset('images/avatar_owner.png') }}">
                                    @else
                                        <img src="{{ asset('storage/'. $bid->user->file->path) }}">
                                    @endif
                                </div>
                                <div class="product-info">
                                    <a href="#" class="product-title"> 
                                        {{ $request->bid_request->user->name }}
                                    </a>
                                    <span class="label label-warning pull-right">
                                        Rp. {{ number_format($request->bid_request->price) }}
                                    </span>
                                    <span class="product-description">
                                        Waktu Pengerjaan {{ $request->bid_request->processing_time }} Hari
                                    </span>
                                    <p>
                                        {{ $request->bid_request->description }}
                                    </p>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="box-footer">
                        @if( $request->status == \App\Models\Request::STATUS_FINISH )
                            <button class="btn btn-primary pull-right" id="btnTransaction">
                                Lanjut Transaksi
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>

	<!-- bid -->
    <div class="modal fade" tabindex="-1" role="dialog" id="bidModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="formBid">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Pilih Pengrajin</h4>
                    </div>

                    <div class="modal-body">
                    	<input type="hidden" name="id_request" id="id_request" value="{{ $request->id }}">
                        <div class="form-group">
                            <label class="control-label">
                            	Tentukan pengrajin yang ingin anda pilih untuk mengerjakan permintaan barang anda
                            </label>

                            <select class="form-control" id="bid_request" name="bid_request">
                                <option value="">-- Pilih Salah Satu --</option>
                                @foreach ($request->bid_requests as $bid)
                                    <option value="{{ $bid->id }}">
                                    	{{ $bid->user->name }} - Rp.{{ number_format($bid->price) }} - Proses : {{ $bid->processing_time }} Hari
                                    </option>
                                @endforeach                        
                            </select>
                            <span class="help-block"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                            Tidak
                        </button>
                        <button type="submit" class="btn btn-primary" data-loading-text="<i class='fa fa-spinner fa-spin'></i>">
                        	Ya
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- transaction -->
    <div class="modal fade" tabindex="-1" role="dialog" id="transactionModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="formTransaction">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Transaksi</h4>
                    </div>

                    <div class="modal-body">
                        <p id="del-success">Anda yakin ingin melanjutkan ke transaksi ?</p>
                        
                        <input type="hidden" name="total_price" value="{{ $request->price }}">
                        <input type="hidden" name="arr_product[]" value="{{ $request->product_id }}">
                        <input type="hidden" name="arr_price[]" value="{{ $request->price }}">
                        <input type="hidden" name="arr_quantity[]" value="{{ $request->quantity }}">
                        <input type="hidden" name="type" value="{{ \App\Models\Transaction::TYPE_REQUEST }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                            Tidak
                        </button>
                        <button type="submit" class="btn btn-primary" data-loading-text="<i class='fa fa-spinner fa-spin'></i>">
                            Ya
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js')
	<script>
        jQuery(document).ready(function($){
        	// Checking Session
	    	@if( session('success') )
	    		heading: 'Success',
	    		text : "{{ session('success') }}",
	            position : 'top-right',
	            allowToastClose : true,
	            showHideTransition : 'fade',
	            icon : 'success',
	            loader : false
	    	@endif

	    	@if ( session('error') )
	            $.toast({
	            	heading: 'Error',
	                text : "{{ session('error') }}",
	                position : 'top-right',
	                allowToastClose : true,
	                showHideTransition : 'fade',
	                icon : 'error',
	                loader : false,
	                hideAfter: 5000
	            });
	        @endif

	        // bid
            $('#btnBid').click(function () {
                url = '{{ route("member.request.bid") }}';
                $('#bidModal').modal('show');
            });

            $('#formBid').submit(function (event) {
                event.preventDefault();
                $('#formBid div.form-group').removeClass('has-error');
                $('#formBid .help-block').empty();
                $('#formBid button[type=submit]').button('loading');

                var formData = new FormData($("#formBid")[0]);

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    processData : false,
                    contentType : false,   
                    cache: false,

                    success: function (response) {
                        if (response.success) {
                            $.toast({
                                heading: 'Success',
                                text : response.message,
                                position : 'top-right',
                                allowToastClose : true,
                                showHideTransition : 'fade',
                                icon : 'success',
                                loader : false
                            });

                            setTimeout(function () { 
                                location.reload();
                            }, 2000);

                            $('#bidModal').modal('hide');
                        }
                        else {
                            $.toast({
                                heading: 'Error',
                                text : response.message,
                                position : 'top-right',
                                allowToastClose : true,
                                showHideTransition : 'fade',
                                icon : 'error',
                                loader : false
                            });
                        }

                        $('#formBid button[type=submit]').button('reset');
                    },

                    error: function(response){
                        if (response.status === 422) {
                            // form validation errors fired up
                            var error = response.responseJSON.errors;
                            var data = $('#formBid').serializeArray();
                            $.each(data, function(key, value){
                                if( error[data[key].name] != undefined ){
                                    var elem;
                                    if( $("#formBid input[name='" + data[key].name + "']").length )
                                        elem = $("#formBid input[name='" + data[key].name + "']");
                                    else if( $("#formBid select[name='" + data[key].name + "']").length )
                                        elem = $("#formBid select[name='" + data[key].name + "']");
                                    else
                                        elem = $("#formBid textarea[name='" + data[key].name + "']");
                                    
                                    elem.parent().find('.help-block').text(error[data[key].name]);
                                    elem.parent().find('.help-block').show();
                                    elem.parent().parent().addClass('has-error');
                                }
                            });
                        }
                        else if (response.status === 400) {
                            // Bad Client Request
                            $.toast({
                                heading: 'Error',
                                text : response.responseJSON.message,
                                position : 'top-right',
                                allowToastClose : true,
                                showHideTransition : 'fade',
                                icon : 'error',
                                loader : false,
                                hideAfter: 5000
                            });
                        }
                        else {
                            $.toast({
                                heading: 'Error',
                                text : "Whoops, looks like something went wrong.",
                                position : 'top-right',
                                allowToastClose : true,
                                showHideTransition : 'fade',
                                icon : 'error',
                                loader : false,
                                hideAfter: 5000
                            });
                        }
                        $('#formBid button[type=submit]').button('reset');
                    }
                });
            });

            $('#btnTransaction').click(function () {
                $('#transactionModal').modal('show');
                url = '{{ route('member.transaction.store') }}';
            });

            $('#formTransaction').submit(function (event) {
                event.preventDefault();
                $('#formTransaction button[type=submit]').button('loading');

                var _data = $("#formTransaction").serialize();
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: _data,
                    dataType: 'json',
                    cache: false,

                    success: function (response) {
                        if (response.success) {
                            $.toast({
                                heading: 'Success',
                                text : response.message,
                                position : 'top-right',
                                allowToastClose : true,
                                showHideTransition : 'fade',
                                icon : 'success',
                                loader : false
                            });

                            setTimeout(function () { 
                                location.reload();
                            }, 2000);

                            $('#cartModal').modal('hide');
                        }
                        else{
                            $.toast({
                                heading: 'Error',
                                text : response.message,
                                position : 'top-right',
                                allowToastClose : true,
                                showHideTransition : 'fade',
                                icon : 'error',
                                loader : false
                            });                         
                        }
                        $('#formTransaction button[type=submit]').button('reset');
                    },

                    error: function(response){
                        if (response.status === 422) {
                            // form validation errors fired up
                            var error = response.responseJSON.errors;
                            var data = $('#formTransaction').serializeArray();
                            $.each(data, function(key, value){
                                console.log(data[key].name);

                                if( error[data[key].name] != undefined ){
                                    var elem = $("#formTransaction input[name='" + data[key].name + "']").length ? $("#formTransaction input[name='" + data[key].name + "']") : $("#formTransaction select[name='" + data[key].name + "']");
                                    elem.parent().find('.help-block').text(error[data[key].name]);
                                    elem.parent().find('.help-block').show();
                                    elem.parent().parent().addClass('has-error');
                                }
                            });
                        }
                        else if (response.status === 400) {
                            // Bad Client Request
                            $.toast({
                                heading: 'Error',
                                text : response.responseJSON.message,
                                position : 'top-right',
                                allowToastClose : true,
                                showHideTransition : 'fade',
                                icon : 'error',
                                loader : false,
                                hideAfter: 5000
                            });
                        }
                        else {
                            $.toast({
                                heading: 'Error',
                                text : "Whoops, looks like something went wrong.",
                                position : 'top-right',
                                allowToastClose : true,
                                showHideTransition : 'fade',
                                icon : 'error',
                                loader : false,
                                hideAfter: 5000
                            });
                        }
                        $('#formTransaction button[type=submit]').button('reset');
                    }
                });
            });
        });
    </script>
@endsection