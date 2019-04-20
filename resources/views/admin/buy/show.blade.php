@extends('layouts.admin')

@section('header')
	<h1>
		Detail Pembelian Barang Bekas
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li>Daftar Pembelian Barang Bekas</li>
		<li class="active">Detail Pembelian Barang Bekas</li>
	</ol>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-4">
			<div class="box box-success">
				<div class="box-header with-border">
					<h3 class="box-title">
						Detail Penjual {{ $buy->category->name }}
					</h3>
				</div>
				<div class="box-body box-profile">
					<ul class="list-group list-group-unbordered">
						<li class="list-group-item">
							<b>Nama Penjual</b> 
							<p class="pull-right">{{ $buy->user->name }}</p>
						</li>
						<li class="list-group-item">
							<b>No. HP</b>
							<p class="pull-right">
								@if( $buy->user->phone != null )
									{{ $buy->user->phone }}
								@else
									-
								@endif	
							</p>
						</li>
						<li class="list-group-item">
							<b>Email</b> 
							<p class="pull-right">{{ $buy->user->email }}</p>
						</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-md-8">
			<div class="box box-success">
				<div class="box-header with-border">
					<h3 class="box-title">
						Detail Pembelian {{ $buy->category->name }}
					</h3>
				</div>
				<div class="box-body box-profile">
					<ul class="list-group list-group-unbordered">
						<li class="list-group-item">
							<b>Berat</b> 
							<p class="pull-right">{{ $buy->weight }} kg</p>
						</li>
						<li class="list-group-item">
							<b>Harga</b> 
							<p class="pull-right">Rp. {{ number_format($buy->price) }}</p>
						</li>
						<li class="list-group-item">
							<b>Alamat</b> 
							<p class="pull-right">{{ $buy->address }}</p>
						</li>
					</ul>
				</div>
				@if( $buy->status == \App\Models\Buy::STATUS_PENDING )
					<div class="box-footer">
						<button class="btn btn-primary pull-right" id="btnApprove">
							<i class="fa fa-check"></i>
							Setuju
						</button>
						<button class="btn btn-danger pull-left" id="btnReject">
							<i class="fa fa-check"></i>
							Tolak
						</button>
					</div>
				@endif
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
                        <h4 class="modal-title">Pembelian Disetujui</h4>
                    </div>

                    <div class="modal-body">
                        <p id="del-success">Anda yakin ingin menyetujui Pembelian ini ?</p>
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
                        <h4 class="modal-title">Pembelian Ditolak</h4>
                    </div>

                    <div class="modal-body">
                        <p id="del-success">Anda yakin ingin menolak Pembelian ini ?</p>

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

            // reject
            $('#btnReject').click(function () {
                url = '{{ route("admin.buy.reject", ['id' => $buy->id]) }}';

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
                url = '{{ route("admin.buy.approve", ['id' => $buy->id]) }}';

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