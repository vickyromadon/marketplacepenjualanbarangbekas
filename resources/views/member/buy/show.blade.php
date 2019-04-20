@extends('layouts.app')

@section('header')
	<h1>{{ $buy->name }}</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li>Jual Barang Bekas</li>
		<li class="active">{{ $buy->name }}</li>
	</ol>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-8">
			<div class="box box-danger">
				<form class="form-horizontal" method="POST" id="formSell" autocomplete="off">
					<div class="box-header with-border">
						<h3 class="box-title">Form penjualan {{ $buy->name }}</h3>
					</div>
					<div class="box-body">
						<div class="col-md-12">
							<input type="hidden" name="id_category" id="id_category" value="{{ $buy->id }}">
							
							<div class="form-group">
								<label class="control-label">
									Berat {{ $buy->name }}
								</label>
								
								<input type="number" class="form-control" id="weight" name="weight" placeholder="Masukkan berat {{ $buy->name }} dalam satuan (kg)" min="{{ $buy->min_buy }}">	
								<span class="help-block"></span>
							</div>

							<div class="form-group">
								<label class="control-label">
									Alamat Penjemputan
								</label>
								
								<textarea name="address" id="address" class="form-control" placeholder="Masukkan alamat lengkap untuk memudahkan menjemput barang bekas"></textarea>	
								<span class="help-block"></span>
							</div>
						</div>
					</div>
					<div class="box-footer">
						<button type="submit" class="btn btn-success pull-right" data-loading-text="<i class='fa fa-spinner fa-spin'></i>">
							<i class="fa fa-money"></i>
							Jual
						</button>
					</div>
				</form>
			</div>
		</div>
		<div class="col-md-4">
			<div class="row">
				<div class="col-md-12">
					<div class="box box-danger">
						<div class="box-header with-border text-center">
							<h3 class="box-title">Harga {{ $buy->name }}</h3>
						</div>
						<div class="box-body text-center">
							<b style="font-size: 2em;">
								Rp. {{ number_format($buy->price_buy) }} / kg
							</b>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="box box-danger">
						<div class="box-header with-border text-center">
							<h3 class="box-title">Minimal Berat Yang Dapat di Jual</h3>
						</div>
						<div class="box-body text-center">
							<b style="font-size: 2em;">
								{{ number_format($buy->min_buy) }} kg
							</b>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="box box-danger">
						<div class="box-header with-border text-center">
							<h3 class="box-title">Harga {{ $buy->name }} anda</h3>
						</div>
						<div class="box-body text-center">
							<p id="resultPrice" style="font-size: 2em;">Rp. 0</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('js')
	<script type="text/javascript">
        jQuery(document).ready(function($){
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

            // function format currency
        	function number_format (number, decimals, dec_point, thousands_sep) {
			    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
			    var n = !isFinite(+number) ? 0 : +number,
			        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
			        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
			        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
			        s = '',
			        toFixedFix = function (n, prec) {
			            var k = Math.pow(10, prec);
			            return '' + Math.round(n * k) / k;
			        };

			    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
			    if (s[0].length > 3) {
			        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
			    }
			    if ((s[1] || '').length < prec) {
			        s[1] = s[1] || '';
			        s[1] += new Array(prec - s[1].length + 1).join('0');
			    }
			    return s.join(dec);
			}

			// Submit Form setting
            $('#formSell').submit(function (event) {
                event.preventDefault();
                $('#formSell button[type=submit]').button('loading');
                $('#formSell div.form-group').removeClass('has-error');
                $('#formSell .help-block').empty();

                var _data = $("#formSell").serialize();

                $.ajax({
                    url: "{{ route('member.buy.store') }}",
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

                        $('#formSell')[0].reset();
                        $('#formSell button[type=submit]').button('reset');
                    },
                    error: function(response){
                        if (response.status === 422) {
                            // form validation errors fired up
                            var error = response.responseJSON.errors;
                            var data = $('#formSell').serializeArray();
                            $.each(data, function(key, value){
                                if( error[data[key].name] != undefined ){
                                    var elem;
                                    if( $("#formSell input[name='" + data[key].name + "']").length )
                                        elem = $("#formSell input[name='" + data[key].name + "']");
                                    else if( $("#formSell textarea[name='" + data[key].name + "']").length )
                                        elem = $("#formSell textarea[name='" + data[key].name + "']");
                                    else
                                        elem = $("#formSell select[name='" + data[key].name + "']");
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
                        $('#formSell button[type=submit]').button('reset');
                    }
                });
            });

            $("#formSell input[name='weight']").on('input',function(e) {
				var result = parseInt($(this).val() * {{ $buy->price_buy }});

				$('#resultPrice').text('Rp. ' + number_format(result, 0, ',', ','));
			});
        });
    </script>
@endsection