@extends('layouts.app')

@section('header')
	<h1>
	Detail Riwayat Transaksi
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li>Riwayat Transaksi</li>
		<li class="active">Detail Riwayat Transaksi</li>
	</ol>
@endsection

@section('content')
	<div class="row">
		@foreach($data as $item)
			<div class="col-md-4">
				<div class="box box-danger">
					<div class="box-header with-border text-center">
						<h3 class="box-title">{{ $item[0]->name }}</h3>	
					</div>
					<div class="box-body box-profile">
						<div class="row">
							<div class="col-md-12">
								<img class="img-responsive img-thumbnail" src="{{ asset('storage/'.$item[0]->file->path) }}" style="width: 100%; height: 200px;">
							</div>
						</div>
						<div class="row">
							<div class="col-md-12">
								<ul class="list-group list-group-unbordered">
									<li class="list-group-item">
										<b>Pengrajin</b> 
										<p class="pull-right">{{ $item[0]->user->name }}</p>
									</li>
									<li class="list-group-item">
										<b>Harga</b> 
										<p class="pull-right">Rp. {{ number_format($item[1]) }}</p>
									</li>
									<li class="list-group-item">
										<b>Banyak Barang</b> 
										<p class="pull-right">{{ $item[2] }}</p>
									</li>
									<li class="list-group-item">
										<b>Total Harga</b> 
										<p class="pull-right" style="font-weight: bolder; font-size: 1em;">Rp. {{ number_format($item[1] * $item[2]) }}</p>
									</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		@endforeach			
	</div>

    @if( $transaction->status != \App\Models\Transaction::STATUS_NOT_PAYMENT )
        <div class="row">
            <div class="col-md-12">
                <div class="box box-danger">
                    <div class="box-body">
                        <p>
                            <span style="font-weight: bolder;">Status Transaksi : </span>
                            @if( $transaction->status == \App\Models\Transaction::STATUS_PENDING )
                                Sedang menunggu persetujuan admin
                            @elseif( $transaction->status == \App\Models\Transaction::STATUS_CANCEL )
                                Di Batalkan
                            @elseif( $transaction->status == \App\Models\Transaction::STATUS_APPROVE )
                                Transaksi di setujui oleh admin
                            @elseif( $transaction->status == \App\Models\Transaction::STATUS_REJECT )
                                Transaksi di tolak
                            @elseif( $transaction->status == \App\Models\Transaction::STATUS_FINISH )
                                Transaksi Selesai
                            @endif
                        </p>
                        <p>
                            <span style="font-weight:bolder;">Note : </span>
                            {{ $transaction->note == null ? '-' : $transaction->note }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    @endif
	
	@if( $transaction->status == \App\Models\Transaction::STATUS_NOT_PAYMENT || $transaction->status == \App\Models\Transaction::STATUS_REJECT )
		<div class="row">
			<div class="col-md-12">
				<div class="box box-danger">
					<div class="box-body">
						<div class="row">
							<div class="col-md-12">
								<p style="font-size: 1.5em;">
									Silahkan transfer uang sebesar <span style="font-weight:bolder;">Rp. {{ number_format($transaction->total_price) }}</span> ke salah satu rekening di bawah ini :
								</p>
							</div>
						</div>
						<div class="row">
							@foreach($banks as $bank)
								<div class="col-md-3">
									<div class="box box-default">
										<div class="box-header with-border">
											<h3 class="box-title">{{ $bank->name }}</h3>		
										</div>
										<div class="box-body box-profile">
											<ul class="list-group list-group-unbordered">
												<li class="list-group-item">
													<b>Atas Nama</b> 
													<p class="pull-right">{{ $bank->owner }}</p>
												</li>
												<li class="list-group-item">
													<b>No. Rekening</b> 
													<p class="pull-right">{{ $bank->number }}</p>
												</li>
											</ul>
										</div>
									</div>
								</div>
							@endforeach
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="well">
			        <div class="row">
			        	<div class="col-md-8">
			                <p style="font-size: 1.5em;">
			                	Jika sudah melakukan transfer uang, Silahkan konfirmasi pembayaran anda.
			                </p>
			            </div>
			            <div class="col-md-4">
			                <div class="row">
		                		<a id="btnConfirmation" class="btn btn-lg btn-success btn-block" href="#">
				                	<i class="fa fa-check"></i> Konfirmasi Pembayaran
				                </a>
				            </div>
				            <br>
				            <div class="row">
		                		<a id="btnCancel" class="btn btn-lg btn-warning btn-block" href="#">
				                	<i class="fa fa-close"></i> Batalkan Pembayaran
				                </a>
			                </div>
			            </div>
			        </div>
			    </div>
			</div>
		</div>
	@endif

	<!-- add and edit -->
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
                                <label class="col-sm-3 control-label">Nama Penerima</label>
                                
                                <div class="col-sm-9">
                                    <input name="name" id="name" class="form-control" placeholder="Masukkan Nama Lengkap">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">No. HP Penerima</label>
                                
                                <div class="col-sm-9">
                                    <input name="phone" id="phone" class="form-control" placeholder="Masukkan No. HP Yang Dapat Dihubungi">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Alamat Penerima</label>
                                
                                <div class="col-sm-9">
                                    <textarea name="address" id="address" class="form-control" placeholder="Masukkan Alamat Lengkap"></textarea>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Bank Transfer</label>
                                
                                <div class="col-sm-9">
                                    <select name="bank" id="bank" class="form-control">
                                    	<option value="">-- Pilih Salah Satu --</option>
                                    	@foreach($banks as $bank)
                                    		<option value="{{ $bank->id }}">{{ $bank->name }} - ({{ $bank->number }})</option>
                                    	@endforeach
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            </div>
							
							<div class="form-group">
                                <label class="col-sm-3 control-label">Tanggal Transfer</label>
                                
                                <div class="col-sm-9">
                                    <input type="text" name="transfer_date" id="transfer_date" class="form-control" placeholder="PIlih Tanggal">
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Bukti Transfer</label>
                                
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

    <!-- cancel -->
    <div class="modal fade" tabindex="-1" role="dialog" id="cancelModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="formCancel">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Batalkan Transaksi</h4>
                    </div>

                    <div class="modal-body">
                        <p id="del-success">Anda yakin ingin membatalkan transaksi ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                            Tidak
                        </button>
                        <button type="submit" class="btn btn-primary" data-loading-text="<i class='fa fa-spinner fa-spin'></i>">	Ya
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

            // cancel
            $('#btnCancel').click(function () {
                $('#formCancel')[0].reset();
                $('#formCancel .modal-title').text("Batalkan Transaksi");
                $('#formCancel div.form-group').removeClass('has-error');
                $('#formCancel .help-block').empty();
                $('#formCancel button[type=submit]').button('reset');

                url = '{{ route("member.transaction.cancel", ['id' => $transaction->id]) }}';

                $('#cancelModal').modal('show');
            });

            $('#formCancel').submit(function (event) {
                event.preventDefault();
                $('#formCancel button[type=submit]').button('loading');
                $('#formCancel div.form-group').removeClass('has-error');
                $('#formCancel .help-block').empty();

                var _data = $("#formCancel").serialize();
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

                            $('#cancelModal').modal('hide');

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
                        $('#formCancel button[type=submit]').button('reset');
                    },

                    error: function(response){
                        if (response.status === 422) {
                            // form validation errors fired up
                            var error = response.responseJSON.errors;
                            var data = $('#formCancel').serializeArray();
                            $.each(data, function(key, value){
                                console.log(data[key].name);

                                if( error[data[key].name] != undefined ){
                                    var elem = $("#formCancel input[name='" + data[key].name + "']").length ? $("#formCancel input[name='" + data[key].name + "']") : $("#formCancel select[name='" + data[key].name + "']");
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
                        $('#formCancel button[type=submit]').button('reset');
                    }
                });
            });

            // Confirmation
            $('#btnConfirmation').click(function () {
                $('#formConfirmation')[0].reset();
                $('#formConfirmation .modal-title').text("Isi Form Transaksi Dengan Benar");
                $('#formConfirmation div.form-group').removeClass('has-error');
                $('#formConfirmation .help-block').empty();
                $('#formConfirmation button[type=submit]').button('reset');

                $('#formConfirmation .modal-body .form-horizontal').append('<input type="hidden" name="_method" value="PUT">');
                url = '{{ route("member.transaction.index") }}' + '/' + {{ $transaction->id }};

                $('#id').val('{{ $transaction->id }}');

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

            //Date picker
            $('#transfer_date').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
                endDate: '+1d',
                datesDisabled: '+1d',
            });
        });
    </script>
@endsection