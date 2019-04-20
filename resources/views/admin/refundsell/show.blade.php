@extends('layouts.admin')

@section('header')
	<h1>Detail Pengembalian Dana</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li>Pengembalian Dana</li>
		<li class="active">Detail Pengembalian Dana</li>
	</ol>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box box-success">
				<div class="box-header with-border">
					<h3 class="box-title">
						Bank Pengrajin {{ $refund->user->name }}
					</h3>
				</div>
				<div class="box-body no-padding">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>No</th>
								<th>Nama Bank</th>
								<th>Nama Pemilik</th>
								<th>Nomor Rekening</th>
							</tr>
						</thead>
						<tbody>
							@php
								$i = 0;
							@endphp
							@foreach( $banks as $bank )
								<tr>
									<td>{{ $i+=1 }}</td>
									<td>{{ $bank->name }}</td>
									<td>{{ $bank->owner }}</td>
									<td>{{ $bank->number }}</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-4">
			<div class="box box-success">
				<div class="box-header">
					<h3 class="box-title">
						Detail Pengembalian Dana Penjualan
					</h3>
				</div>
				<div class="box-body box-profile">
					<ul class="list-group list-group-unbordered">
						<li class="list-group-item">
							<b>Nama Pengrajin</b> <a class="pull-right">{{ $refund->user->name }}</a>
						</li>
						<li class="list-group-item">
							<b>Nomor HP</b> <a class="pull-right">{{ $refund->user->phone == null ? '-' : $refund->user->phone }}</a>
						</li>
						<li class="list-group-item">
							<b>Nominal</b> <a class="pull-right">Rp. {{ number_format($refund->price) }}</a>
						</li>
						<li class="list-group-item">
							<b>Status Pengiriman</b> <a class="pull-right">{{ $refund->delivery->status }}</a>
						</li>
						<li class="list-group-item">
							<b>Status Pengembalian Dana</b> <a class="pull-right">{{ $refund->status }}</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-md-8">
			@if( $refund->status == \App\Models\Refund::STATUS_PENDING )
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title">
							Form Pengembalian Dana
						</h3>
					</div>
					<div class="box-body">
						<form class="form-horizontal" method="POST" id="formRefund">
							<div class="form-group">
								<label class="col-sm-2 control-label">Pilih Bank</label>

								<div class="col-sm-10">
									<select name="bank" id="bank" class="form-control">
										<option value="">-- Pilih Salah Satu --</option>
										@foreach( $banks as $bank )
											<option value="{{ $bank->id }}">{{ $bank->name }} ({{ $bank->number }})</option>
										@endforeach
									</select>
									
									<span class="help-block"></span>
								</div>
							</div>
							<div class="form-group">
			                    <div class="col-sm-offset-2 col-sm-10">
			                      	<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-save"></i> Simpan</button>
			                    </div>
		                  	</div>
						</form>
					</div>
				</div>
			@else
				<div class="box box-success">
					<div class="box-header with-border">
						<h3 class="box-title">
							Catatan Pengembalian Dana
						</h3>
					</div>
					<div class="box-body">
						Pengembalian dana telah berhasil di kirim ke pengrajin <i><b>{{ $refund->user->name }}</b></i> dengan keterangan bank sebagai berikut :
						
						<br><br>
						
						<table>
							<tr>
								<td>Nama Bank</td>
								<td> : </td>
								<td>{{ $refund->bank->name }}</td>
							</tr>
							<tr>
								<td>Nama Pemilik</td>
								<td> : </td>
								<td>{{ $refund->bank->owner }}</td>
							</tr>
							<tr>
								<td>Nomor Rekening</td>
								<td> : </td>
								<td>{{ $refund->bank->number }}</td>
							</tr>
						</table>

						<br>

						Telah di transfer pada tanggal <i><b>{{ $refund->updated_at }}</b></i>
					</div>
				</div>
			@endif
		</div>
	</div>
@endsection

@section('js')
	<script type="text/javascript">
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

        // Submit Form setting
       	$('#formRefund').submit(function (event) {
            event.preventDefault();
		 	$('#formRefund button[type=submit]').button('loading');
		 	$('#formRefund div.form-group').removeClass('has-error');
	        $('#formRefund .help-block').empty();

		 	var _data = $("#formRefund").serialize();

		 	$.ajax({
                url: "{{ route('admin.refund_sell.finish', ['id' => $refund->id]) }}",
                method: 'POST',
                data: _data,
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
                    } else {
                        $.toast({
                            heading: 'Error',
                            text : response.message,
                            position : 'top-right',
                            allowToastClose : true,
                            showHideTransition : 'fade',
                            icon : 'error',
                            loader : false,
                            hideAfter: 5000
                        });
                    }

                    $('#formRefund')[0].reset();
                    $('#formRefund button[type=submit]').button('reset');
                },
                error: function(response){
                	if (response.status === 422) {
                        // form validation errors fired up
                        var error = response.responseJSON.errors;
                        var data = $('#formRefund').serializeArray();
                        $.each(data, function(key, value){
                            console.log(data[key].name);
                            if( error[data[key].name] != undefined ){
                                var elem = $("#formRefund input[name='" + data[key].name + "']").length ? $("#formRefund input[name='" + data[key].name + "']") : $("#formRefund select[name='" + data[key].name + "']");
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
                    $('#formRefund button[type=submit]').button('reset');
                }
            });
		});
	</script>
@endsection