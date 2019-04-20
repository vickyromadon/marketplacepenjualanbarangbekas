@extends('layouts.owner')

@section('header')
	<h1>Detail Kerajinan</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li>Sedang Dalam Pengerjaan</li>
		<li class="active">Detail Kerajinan</li>
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
							<div class="row">
								<div class="col-md-12">
									<p class="pull-left">
										<i class="fa fa-clock-o"></i> Tanggal Posting : 
										{{ date_format($request->created_at,"d F Y - H:i") }}
									</p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<p class="pull-left">
										<i class="fa fa-clock-o"></i> Tanggal Pengerjaan : 
										{{ date_format($request->bid_request->created_at,"d F Y - H:i") }}
									</p>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="row">
								<div class="col-md-12">
									<p class="pull-right">
										<i class="fa fa-user-o"></i> Pemesan :
										{{ $request->user->name }}
									</p>
								</div>
							</div>
							<div class="row">
								<div class="col-md-12">
									<p class="pull-right">
										<i class="fa fa-user"></i> Pengrajin :
										{{ $request->bid_request->user->name }}
									</p>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							{!! $request->description !!}
						</div>
					</div>
				</div>
                @if( $request->status == \App\Models\Request::STATUS_ON_PROGRESS )
    				<div class="box-footer">
    					<button id="btnConfirmation" class="btn btn-success pull-right">
    						Konfirmasi Selesai
    					</button>
    				</div>
                @endif
			</div>
		</div>
	</div>

	<!-- add and edit -->
    <div class="modal fade" tabindex="-1" role="dialog" id="productModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="formProduct" autocomplete="off">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title"></h4>
                    </div>

                    <div class="modal-body">
                        <div class="form-horizontal">
                            <input type="hidden" id="id" name="id" value="{{ $request->id }}">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Kategori</label>
                                
                                <div class="col-md-9">
                                    <select class="form-control" id="category" name="category" disabled>
                                        <option value="{{ $request->category->id }}">{{ $request->category->name }}</option>                        
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Nama Produk</label>
                                
                                <div class="col-sm-9">
                                    <input class="form-control" type="text" id="name" name="name" placeholder="Nama Produk" value="{{ $request->title }}" disabled>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Kuantitas</label>
                                
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" id="quantity" name="quantity" min="0" placeholder="Kuantitas Produk" value="1" disabled>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Harga</label>
                                
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" id="price" name="price" min="0" placeholder="Harga Produk" value="{{ $request->price }}" disabled>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">
                                    Gambar Produk
                                </label>
                                
                                <div class="col-sm-9 fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                        <img id="img-upload" src="{{ asset('images/default.jpg') }}" style="width: 200px; height: 150px;">
                                    </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
                                    <div>
                                        <span class="btn btn-default btn-file">
                                            <span class="fileinput-new">Pilih Gambar</span>
                                            <span class="fileinput-exists">Rubah</span>
                                            <input type="file" id="file_id" name="file_id">
                                        </span>
                                        <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">
                                            Hapus
                                        </a>
                                    </div>
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
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css">
@endsection

@section('js')
    <script src="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js"></script>
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

	        // add
            $('#btnConfirmation').click(function () {
                $('#formProduct')[0].reset();
                $('#formProduct .modal-title').text("Konfirmasi Permintaan");
                $('#formProduct div.form-group').removeClass('has-error');
                $('#formProduct .help-block').empty();
                $('#formProduct button[type=submit]').button('reset');

                url = '{{ route("owner.request.confirmation") }}';

                $('#formProduct #img-upload').attr('src', "{{ asset('images/default.jpg') }}");

                $('#productModal').modal('show');
            });

            $('#formProduct').submit(function (event) {
                event.preventDefault();
                $('#formProduct div.form-group').removeClass('has-error');
                $('#formProduct .help-block').empty();
                $('#formProduct button[type=submit]').button('loading');

                var formData = new FormData($("#formProduct")[0]);

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

                            $('#productModal').modal('hide');

                            setTimeout(function () { 
                                location.reload();
                            }, 2000);
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

                        $('#formProduct button[type=submit]').button('reset');
                    },

                    error: function(response){
                        if (response.status === 422) {
                            // form validation errors fired up
                            var error = response.responseJSON.errors;
                            var data = $('#formProduct').serializeArray();
                            $.each(data, function(key, value){
                                if( error[data[key].name] != undefined ){
                                    var elem;
                                    if( $("#formProduct input[name='" + data[key].name + "']").length )
                                        elem = $("#formProduct input[name='" + data[key].name + "']");
                                    else if( $("#formProduct select[name='" + data[key].name + "']").length )
                                        elem = $("#formProduct select[name='" + data[key].name + "']");
                                    else
                                        elem = $("#formProduct textarea[name='" + data[key].name + "']");
                                    
                                    elem.parent().find('.help-block').text(error[data[key].name]);
                                    elem.parent().find('.help-block').show();
                                    elem.parent().parent().addClass('has-error');
                                }
                            });
                            if(error['file_id'] != undefined){
                                $("#formProduct input[name='file_id']").parent().parent().parent().find('.help-block').text(error['file_id']);
                                $("#formProduct input[name='file_id']").parent().parent().parent().find('.help-block').show();
                                $("#formProduct input[name='file_id']").parent().parent().parent().parent().addClass('has-error');
                            }
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
                        $('#formProduct button[type=submit]').button('reset');
                    }
                });
            });
	    });
    </script>
@endsection