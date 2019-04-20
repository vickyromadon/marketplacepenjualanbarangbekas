@extends('layouts.app')

@section('header')
	<h1>
	Product Detail {{ $product->name }}
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li>Kategori</li>
		<li>{{ $product->category->name }}</li>
		<li class="active">{{ $product->name }}</li>
	</ol>
@endsection

@section('content')
	<div class="row">
        <div class="col-lg-8">
            <p class="pull-left"><i class="fa fa-user-o"></i> Di Posting Oleh {{ $product->user->name }}</p>
            <p class="pull-right"><i class="fa fa-clock-o"></i> Di Posting Pada {{ date_format($product->created_at,"d F Y - H:i") }}</p>

            <hr>

            <img class="img-responsive img-thumbnail" src="{{ asset('storage/'. $product->file->path) }}" style="width: 100%; height: 500px;">

            <hr>

            <p>
            	{!! $product->description !!}
            </p>
        </div>

        <div class="col-lg-4">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-danger">
                        <div class="box-header with-border">
                            <h3 class="box-title">Kontak Penjual</h3>
                        </div>
                        <div class="box-body">
                            <p>
                                <i class="fa fa-envelope"></i>
                                {{ $product->user->email }}
                            </p>
                            <p>
                                <i class="fa fa-phone"></i>
                                {{ $product->user->phone == null ? '-' : $product->user->phone }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="box box-danger">
                        <div class="box-header with-border text-center">
                            <h3 class="box-title"><i class="fa fa-eye fa-lg"></i> Dilihat</h3>
                        </div>
                        <div class="box-body text-center">
                            <p style="font-size: 2em;">
                                {{ $product->view }}
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                     <div class="box box-danger">
                        <div class="box-header with-border text-center">
                            <h3 class="box-title"><i class="fa fa-cubes fa-lg"></i> Persediaan</h3>
                        </div>
                        <div class="box-body text-center">
                            <p style="font-size: 2em;">
                                {{ $product->quantity }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                     <div class="box box-danger">
                        <div class="box-header with-border text-center">
                            <h3 class="box-title"><i class="fa fa-money fa-lg"></i> Harga</h3>
                        </div>
                        <div class="box-body text-center">
                            <p style="font-size: 2em;">
                                Rp. {{ number_format($product->price) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            @if(Auth::user())
                <div class="row">
                    <div class="col-md-12">
                        <button id="btnAddToCart" class="col-md-12 btn btn-primary btn-lg">
                            <i class="fa fa-cart-plus"></i> ADD TO CART
                        </button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- add to cart -->
    @if(Auth::user())
    <div class="modal fade" tabindex="-1" role="dialog" id="addToCartModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="formAddToCart" autocomplete="off">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title"></h4>
                    </div>

                    <div class="modal-body">
                        <div class="form-horizontal">
                            <input type="hidden" id="id_user" name="id_user" value="{{ Auth::user()->id }}">
                            <input type="hidden" id="id_product" name="id_product" value="{{ $product->id }}">

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Banyak Barang</label>
                                
                                <div class="col-sm-9">
                                    <input type="number" id="quantity" name="quantity" class="form-control" placeholder="Masukkan Banyak Barang">
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
    @endif
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

            $('#btnAddToCart').click(function () {
                $('#formAddToCart')[0].reset();
                $('#formAddToCart .modal-title').text("Tambah Ke Keranjang Belanja");
                $('#formAddToCart div.form-group').removeClass('has-error');
                $('#formAddToCart .help-block').empty();
                $('#formAddToCart button[type=submit]').button('reset');

                $('#formAddToCart input[name="_method"]').remove();
                url = '{{ route("member.cart.store") }}';

                $('#addToCartModal').modal('show');
            });

            $('#formAddToCart').submit(function (event) {
                event.preventDefault();
                $('#formAddToCart button[type=submit]').button('loading');
                $('#formAddToCart div.form-group').removeClass('has-error');
                $('#formAddToCart .help-block').empty();

                var _data = $("#formAddToCart").serialize();
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

                            $('#addToCartModal').modal('hide');

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
                        $('#formAddToCart button[type=submit]').button('reset');
                    },

                    error: function(response){
                        if (response.status === 422) {
                            // form validation errors fired up
                            var error = response.responseJSON.errors;
                            var data = $('#formAddToCart').serializeArray();
                            $.each(data, function(key, value){
                                if( error[data[key].name] != undefined ){
                                    var elem = $("#formAddToCart input[name='" + data[key].name + "']").length ? $("#formAddToCart input[name='" + data[key].name + "']") : $("#formAddToCart textarea[name='" + data[key].name + "']");
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
                        $('#formAddToCart button[type=submit]').button('reset');
                    }
                });
            });
        });
    </script>
@endsection