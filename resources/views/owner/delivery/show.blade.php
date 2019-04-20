@extends('layouts.owner')

@section('header')
	<h1>Detail Pengiriman</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li>Pengiriman</li>
		<li class="active">Detail Pengiriman</li>
	</ol>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-4">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">Produk Yang Dibeli</h3>
				</div>
				<div class="box-body box-profile">
					<div class="row">
						<div class="col-md-12">
							<img class="img-responsive img-thumbnail" src="{{ asset('storage/'.$delivery->product->file->path) }}" style="width: 100%; height: 200px;">
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<ul class="list-group list-group-unbordered">
								<li class="list-group-item">
									<b>Nama Produk</b> 
									<p class="pull-right">{{ $delivery->product->name }}</p>
								</li>
								<li class="list-group-item">
									<b>Kuantitas</b> 
									<p class="pull-right">{{ $delivery->quantity }}</p>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-8">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">Data Pengiriman</h3>
						</div>
						<div class="box-body box-profile">
							<ul class="list-group list-group-unbordered">
								<li class="list-group-item">
									<b>Nama</b> 
									<p class="pull-right">{{ $delivery->transaction->name }}</p>
								</li>
								<li class="list-group-item">
									<b>No. HP</b> 
									<p class="pull-right">{{ $delivery->transaction->phone }}</p>
								</li>
								<li class="list-group-item">
									<b>Alamat</b> 
									<p class="pull-right">{{ $delivery->transaction->address }}</p>
								</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			@if( $delivery->status == \App\Models\Delivery::STATUS_PENDING )
				<div class="row">
					<div class="col-md-12">
						<div class="box box-primary">
							<div class="box-body">
								<div class="col-md-8">
									<p style="font-size: 1.5em;">
										Jika sudah melakukan pengiriman barang, Silahkan konfirmasi
									</p>
								</div>
								<div class="col-md-4">
									<button id="btnConfirmation" class="btn btn-success btn-lg btn-block">Konfirmasi Pengiriman</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			@endif
		</div>
	</div>

	<!-- konfirmasi pengiriman -->
    <div class="modal fade" tabindex="-1" role="dialog" id="confirmationModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="formConfirmation" autocomplete="off">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title"></h4>
                    </div>

                    <div class="modal-body">
                        <div class="form-horizontal">
                            <input type="hidden" id="id" name="id">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Nomor Resi</label>
                                
                                <div class="col-sm-9">
                                    <input type="text" name="number_proof" id="number_proof" class="form-control" placeholder="Masukkan Nomor Resi Dengan Benar">
                                    <span class="help-block"></span>
                                </div>
                            </div>
							
							<div class="form-group">
                                <label class="col-sm-3 control-label">Tanggal Kirim</label>
                                
                                <div class="col-sm-9">
                                    <input type="text" name="delivery_at" id="delivery_at" class="form-control" placeholder="PIlih Tanggal">
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Tanggal Tiba</label>
                                
                                <div class="col-sm-9">
                                    <input type="text" name="arrive_at" id="arrive_at" class="form-control" placeholder="PIlih Tanggal">
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Bukti Pengiriman</label>
                                
                                <div class="col-sm-9">
                                    <input type="file" name="proof" id="proof" class="form-control">
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
            $('#btnConfirmation').click(function () {
                $('#formConfirmation')[0].reset();
                $('#formConfirmation .modal-title').text("Isi Form Pengiriman Dengan Benar");
                $('#formConfirmation div.form-group').removeClass('has-error');
                $('#formConfirmation .help-block').empty();
                $('#formConfirmation button[type=submit]').button('reset');

                $('#formConfirmation .modal-body .form-horizontal').append('<input type="hidden" name="_method" value="PUT">');
                url = '{{ route("owner.delivery.index") }}' + '/' + {{ $delivery->id }};

                $('#id').val('{{ $delivery->id }}');

                $('#confirmationModal').modal('show');
            });

            $('#formConfirmation').submit(function (event) {
                event.preventDefault();
                $('#formConfirmation button[type=submit]').button('loading');
                $('#formConfirmation div.form-group').removeClass('has-error');
                $('#formConfirmation .help-block').empty();

                var formData = new FormData($("#formConfirmation")[0]);
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

                            $('#categoryModal').modal('hide');

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
                        $('#formConfirmation button[type=submit]').button('reset');
                    },

                    error: function(response){
                        if (response.status === 422) {
                            // form validation errors fired up
                            var error = response.responseJSON.errors;
                            var data = $('#formConfirmation').serializeArray();
                            $.each(data, function(key, value){
                                if( error[data[key].name] != undefined ){
                                    var elem;
                                    if( $("#formConfirmation input[name='" + data[key].name + "']").length )
                                        elem = $("#formConfirmation input[name='" + data[key].name + "']");
                                    else if( $("#formConfirmation textarea[name='" + data[key].name + "']").length )
                                        elem = $("#formConfirmation textarea[name='" + data[key].name + "']");
                                    else
                                        elem = $("#formConfirmation select[name='" + data[key].name + "']");

                                    elem.parent().find('.help-block').text(error[data[key].name]);
                                    elem.parent().find('.help-block').show();
                                    elem.parent().parent().addClass('has-error');
                                }
                            });

                            if(error['proof'] != undefined){
                                $("#formConfirmation input[name='proof']").parent().find('.help-block').text(error['proof']);
                                $("#formConfirmation input[name='proof']").parent().find('.help-block').show();
                                $("#formConfirmation input[name='proof']").parent().parent().addClass('has-error');
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
                        $('#formConfirmation button[type=submit]').button('reset');
                    }
                });
            });

			//delivery_at
            $('#delivery_at').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
                endDate: '+1d',
                datesDisabled: '+1d',
            });

            //arrive_at
            $('#arrive_at').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
                endDate: '+1d',
                datesDisabled: '+1d',
            });
        });
    </script>
@endsection