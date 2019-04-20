@extends('layouts.owner')

@section('header')
	<h1>Detail Permintaan</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li>Daftar Permintaan</li>
		<li class="active">Detail Permintaan</li>
	</ol>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">
						Detail {{ $request->title }}
					</h3>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-md-12">
							<img class="img-responsive img-thumbnail" src="{{ asset('storage/'. $request->file->path) }}" style="width: 100%; height: 300px;">
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<p class="pull-left">
								<i class="fa fa-clock-o"></i>
								{{ date_format($request->created_at,"d F Y - H:i") }}
							</p>
						</div>
						<div class="col-md-6">
							<p class=" pull-right">
								<i class="fa fa-user-o"></i>
								{{ $request->user->name }}
							</p>
						</div>
					</div>
                    <div class="row">
                        <div class="col-md-12">
                            <p style="font-size: 1.5em;">
                                Banyak kreasi yang dibutuhkan : {{ $request->quantity }}
                            </p>
                        </div>
                    </div>
					<div class="row">
						<div class="col-md-12">
							{!! $request->description !!}
						</div>
					</div>
				</div>
				<div class="box-footer">
					<button id="btnBid" class="btn btn-success pull-right">
						Menawar
					</button>
				</div>
			</div>
		</div>
	</div>

    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        Penawaran Pengrajin
                    </h3>
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
                                    <a href="#" class="product-title"> {{ $bid->user->name }}</a>
                                    
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
            </div>
        </div>
    </div>

	<!-- penawaran -->
    <div class="modal fade" tabindex="-1" role="dialog" id="bidModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="formBid" autocomplete="off">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title"></h4>
                    </div>

                    <div class="modal-body">
                    	<input type="hidden" id="id_request" name="id_request" value="{{ $request->id }}">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Penawaran Harga</label>
                                
                                <div class="col-sm-9">
                                    <input type="text" name="price" id="price" class="form-control" placeholder="Masukkan Harga">
                                    <span class="help-block"></span>
                                </div>
                            </div>
							
							<div class="form-group">
                                <label class="col-sm-3 control-label">Penawaran Hari</label>
                                
                                <div class="col-sm-9">
                                    <input type="number" name="processing_time" id="processing_time" class="form-control" placeholder="Masukkan dalam jumlah hari">
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Deskripsi</label>
                                
                                <div class="col-sm-9">
                                    <textarea name="description" id="description" class="form-control" placeholder="Masukkan deskripsi anda"></textarea>
                                    <span class="help-block"></span>
                                </div>
                            </div>                           
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">
                            Kembali
                        </button>
                        <button type="submit" class="btn btn-primary" data-loading-text="<i class='fa fa-spinner fa-spin'></i>">
                            Konfirmasi
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

            var url;

            // Confirmation
            $('#btnBid').click(function () {
                $('#formBid')[0].reset();
                $('#formBid .modal-title').text("Form Penawaran");
                $('#formBid div.form-group').removeClass('has-error');
                $('#formBid .help-block').empty();
                $('#formBid button[type=submit]').button('reset');

                url = '{{ route("owner.bid_request.bid") }}';

                $('#bidModal').modal('show');
            });

             $('#formBid').submit(function (event) {
                event.preventDefault();
                $('#formBid button[type=submit]').button('loading');
                $('#formBid div.form-group').removeClass('has-error');
                $('#formBid .help-block').empty();

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

                            $('#bidModal').modal('hide');

                            setTimeout(function () { 
                                location.reload();
                            }, 2000);
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
                                    else if( $("#formBid textarea[name='" + data[key].name + "']").length )
                                        elem = $("#formBid textarea[name='" + data[key].name + "']");
                                    else
                                        elem = $("#formBid select[name='" + data[key].name + "']");

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
        });
    </script>
@endsection