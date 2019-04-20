@extends('layouts.admin')

@section('header')
	<h1>Detail Transaksi</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li>Transaksi</li>
		<li class="active">Detail Transaksi</li>
	</ol>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box box-success">
				<div class="box-header with-border">
					<h3 class="box-title">Barang yang di beli</h3>
				</div>
				<div class="box-body no-padding">
					<table class="table table-striped">
						<thead>
							<tr>
								<th>No</th>
								<th>Nama Kreasi</th>
								<th>Pengrajin</th>
								<th>Harga</th>
								<th>Banyak Barang</th>
								<th>Total Harga</th>
								<th>Aksi</th>
							</tr>
						</thead>
						<tbody>
							@php
								$i = 0;
							@endphp
							@foreach($data as $item)
								<tr>
									<td>{{ $i+=1 }}</td>
									<td>{{ $item[0]->name }}</td>
									<td>{{ $item[0]->user->name }}</td>
									<td>Rp. {{ number_format($item[1]) }}</td>
									<td>{{ $item[2] }}</td>
									<td>Rp. {{ number_format($item[1] * $item[2]) }}</td>
									<td>
										<a class="btn btn-primary btn-xs" href="{{ route('member.product.show', ['id' => $item[0]->id]) }}" target="_blank">
											<i class="fa fa-eye"></i>
											Lihat
										</a>
									</td>
								</tr>
							@endforeach
							<tr style="font-size: 2em;">
								<td colspan="5" class="text-center"><b>Total Harga</b></td>
								<td><b>Rp. {{ number_format($transaction->total_price) }}</b></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>	
	</div>

	<div class="row">
		<div class="col-md-4">
			<div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">Data Pembeli</h3>
                        </div>
                        <div class="box-body box-profile">
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>Nama</b> 
                                    <p class="pull-right">{{ $transaction->name }}</p>
                                </li>
                                <li class="list-group-item">
                                    <b>No. HP</b> 
                                    <p class="pull-right">{{ $transaction->phone }}</p>
                                </li>
                                <li class="list-group-item">
                                    <b>Alamat</b> 
                                    <p class="pull-right">{{ $transaction->address }}</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">Bank Penerima</h3>
                        </div>
                        <div class="box-body box-profile">
                            <ul class="list-group list-group-unbordered">
                                <li class="list-group-item">
                                    <b>Nama Bank</b> 
                                    <p class="pull-right">{{ $transaction->bank->name }}</p>
                                </li>
                                <li class="list-group-item">
                                    <b>No. Rekening</b> 
                                    <p class="pull-right">{{ $transaction->bank->number }}</p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>         
            </div>
		</div>
		<div class="col-md-8">
			<div class="box box-success">
				<div class="box-header with-border">
					<h3 class="box-title">Bukti Pembayaran</h3>
				</div>
				<div class="box-body">
					<div class="row">
						<div class="col-md-4">
							<p>
								Tanggal Transfer
							</p>
							<p>
								Bukti Transfer
							</p>
						</div>
						<div class="col-md-8">
							<p>
								{{ $transaction->transfer_date }}
							</p>
							<img class="img-responsive img-thumbnail" src="{{ asset('storage/'. $transaction->file->path) }}" style="width: 100%; height: 500px;">
						</div>
					</div>
				</div>
				@if( $transaction->status == \App\Models\Transaction::STATUS_PENDING )
					<div class="box-footer">
						<button id="btnApprove" class="btn btn-primary pull-right">
							<i class="fa fa-check"></i>
							Diterima
						</button>
						<button id="btnReject" class="btn btn-danger pull-left">
							<i class="fa fa-close"></i>
							Ditolak
						</button>
					</div>
				@endif
			</div>
		</div>
	</div>
    
    @if( $transaction->status == \App\Models\Transaction::STATUS_APPROVE || $transaction->status == \App\Models\Transaction::STATUS_FINISH )
        <div class="row">
            <div class="col-md-12">
                <div class="box box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">Status Pengiriman Barang</h3>
                    </div>
                    <div class="box-body no-padding">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kreasi</th>
                                    <th>Pengrajin</th>
                                    <th>Nomor Resi</th>
                                    <th>Status Pengiriman</th>
                                    <th>Bukti Pengiriman</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $i = 0;
                                @endphp
                                @foreach( $delivery as $item )
                                    <tr>
                                        <td>{{ $i+=1 }}</td>
                                        <td>{{ $item->product->name }}</td>
                                        <td>{{ $item->user->name }}</td>
                                        <td>{{ $item->number_proof == null ? '-' : $item->number_proof }}</td>
                                        <td>
                                            @if( $item->status == \App\Models\Delivery::STATUS_PENDING )
                                                <span class="label bg-orange">Belum Dikim</span>
                                            @elseif( $item->status == \App\Models\Delivery::STATUS_DELIVERY )
                                                <span class="label bg-green">Sudah Dikirim</span>
                                            @else
                                                <span class="label bg-blue">Sudah Dikirim</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if( $item->file != null )
                                                <a href="{{ asset('storage/'. $item->file->path) }}" target="_blank">
                                                    <i class="fa fa-eye"></i>
                                                    Lihat
                                                </a>
                                            @else
                                                Belum Ada
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @if( $transaction->status == \App\Models\Transaction::STATUS_APPROVE )
                        <div class="box-footer">
                            <button id="btnFinish" class="btn btn-primary pull-right">
                                <i class="fa fa-check"></i>
                                Selesai
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- finish -->
    <div class="modal fade" tabindex="-1" role="dialog" id="finishModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="formFinish">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Transaksi Selesai</h4>
                    </div>

                    <div class="modal-body">
                        <p id="del-success">Anda yakin ingin menyelesaikan Transaksi ?</p>
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

	<!-- approve -->
    <div class="modal fade" tabindex="-1" role="dialog" id="approveModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="formApprove">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Transaksi Disetujui</h4>
                    </div>

                    <div class="modal-body">
                        <p id="del-success">Anda yakin ingin menyetujui Transaksi ?</p>
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

    <!-- reject -->
    <div class="modal fade" tabindex="-1" role="dialog" id="rejectModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="formReject">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Transaksi Ditolak</h4>
                    </div>

                    <div class="modal-body">
                        <p id="del-success">Anda yakin ingin menolak Transaksi ?</p>

                        <textarea class="form-control" name="note" placeholder="Masukkan alasan ditolak"></textarea>
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

            var url;

            // finish
            $('#btnFinish').click(function () {
                url = '{{ route("admin.transaction_sell.finish", ['id' => $transaction->id]) }}';

                $('#finishModal').modal('show');
            });

            $('#formFinish').submit(function (event) {
                event.preventDefault();
                $('#formFinish button[type=submit]').button('loading');
                $('#formFinish div.form-group').removeClass('has-error');
                $('#formFinish .help-block').empty();

                var _data = $("#formFinish").serialize();
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

                            $('#finishModal').modal('hide');

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
                        $('#formFinish button[type=submit]').button('reset');
                    },

                    error: function(response){
                        if (response.status === 422) {
                            // form validation errors fired up
                            var error = response.responseJSON.errors;
                            var data = $('#formFinish').serializeArray();
                            $.each(data, function(key, value){
                                if( error[data[key].name] != undefined ){
                                    var elem = $("#formFinish input[name='" + data[key].name + "']").length ? $("#formFinish input[name='" + data[key].name + "']") : $("#formFinish textarea[name='" + data[key].name + "']");
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
                        $('#formFinish button[type=submit]').button('reset');
                    }
                });
            });

            // reject
            $('#btnReject').click(function () {
                url = '{{ route("admin.transaction_sell.reject", ['id' => $transaction->id]) }}';

                $('#rejectModal').modal('show');
            });

            $('#formReject').submit(function (event) {
                event.preventDefault();
                $('#formReject button[type=submit]').button('loading');
                $('#formReject div.form-group').removeClass('has-error');
                $('#formReject .help-block').empty();

                var _data = $("#formReject").serialize();
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

                            $('#rejectModal').modal('hide');

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
                        $('#formReject button[type=submit]').button('reset');
                    },

                    error: function(response){
                        if (response.status === 422) {
                            // form validation errors fired up
                            var error = response.responseJSON.errors;
                            var data = $('#formReject').serializeArray();
                            $.each(data, function(key, value){
                                if( error[data[key].name] != undefined ){
                                    var elem = $("#formReject input[name='" + data[key].name + "']").length ? $("#formReject input[name='" + data[key].name + "']") : $("#formReject textarea[name='" + data[key].name + "']");
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
                        $('#formReject button[type=submit]').button('reset');
                    }
                });
            });

            // approve
            $('#btnApprove').click(function () {
                url = '{{ route("admin.transaction_sell.approve", ['id' => $transaction->id]) }}';

                $('#approveModal').modal('show');
            });

            $('#formApprove').submit(function (event) {
                event.preventDefault();
                $('#formApprove button[type=submit]').button('loading');
                $('#formApprove div.form-group').removeClass('has-error');
                $('#formApprove .help-block').empty();

                var _data = $("#formApprove").serialize();
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

                            $('#approveModal').modal('hide');

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
                        $('#formApprove button[type=submit]').button('reset');
                    },

                    error: function(response){
                        if (response.status === 422) {
                            // form validation errors fired up
                            var error = response.responseJSON.errors;
                            var data = $('#formApprove').serializeArray();
                            $.each(data, function(key, value){
                                if( error[data[key].name] != undefined ){
                                    var elem = $("#formApprove input[name='" + data[key].name + "']").length ? $("#formApprove input[name='" + data[key].name + "']") : $("#formApprove textarea[name='" + data[key].name + "']");
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
                        $('#formApprove button[type=submit]').button('reset');
                    }
                });
            });
        });
    </script>
@endsection