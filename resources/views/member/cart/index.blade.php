@extends('layouts.app')

@section('header')
	<h1>
	Keranjang Belanja
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Keranjang Belanja</li>
	</ol>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="box box-danger">
                <div class="box-body">
                    <div class="col-md-12">
                        <h3>
                            Total Harga Keseluruhan : 
                            <b>Rp. {{ number_format($total_price) }}</b>
                            <button id="btnTransaction" class="btn btn-success"><i class="fa fa-money"></i> Lanjut Transaksi</button>
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
        	<div class="box box-danger">
                <div class="box-body">
                    <div class="table-responsive">
                        <table id="cart_table" class="table table-striped table-bordered table-hover nowrap dataTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Produk</th>
                                    <th>Gambar Produk</th>
                                    <th>Harga</th>
                                    <th>Kuantitas</th>
                                    <th>Total Harga</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- add and edit -->
    <div class="modal fade" tabindex="-1" role="dialog" id="cartModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="formCart" autocomplete="off">
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
                                <label class="col-sm-3 control-label">Kuantitas</label>
                                
                                <div class="col-sm-9">
                                    <input class="form-control" type="number" id="quantity" name="quantity" placeholder="Kuantitas">
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

    <!-- delete -->
    <div class="modal fade" tabindex="-1" role="dialog" id="deleteCartModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('cart.destroy', ['cart' => '#']) }}" method="post" id="formDeleteCart">
                	{{ method_field('DELETE') }}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Hapus Produk</h4>
                    </div>

                    <div class="modal-body">
                        <p id="del-success">Anda yakin ingin menghapus Produk ?</p>
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
                        
                        <input type="hidden" name="total_price" value="{{ $total_price }}">
                        @foreach($carts as $cart)
                            <input type="hidden" name="arr_product[]" value="{{ $cart->product_id }}">
                            <input type="hidden" name="arr_price[]" value="{{ $cart->price }}">
                            <input type="hidden" name="arr_quantity[]" value="{{ $cart->quantity }}">
                        @endforeach
                        <input type="hidden" name="type" id="type" value="{{ \App\Models\Transaction::TYPE_SELL }}">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                            Tidak
                        </button>
                        <button type="submit" class="btn btn-primary" data-loading-text="<i class='fa fa-spinner fa-spin'></i>">    Ya
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

	        var table = $('#cart_table').DataTable({
	            "bFilter": true,
	            "processing": true,
	            "serverSide": true,
	            "lengthChange": true,
	            "ajax": {
	                "url": "{{ route('member.cart.index') }}",
	                "type": "POST",
	                "data" : {}
	            },
	            "language": {
	                "emptyTable": "Tidak Ada Data Tersedia",
	            },
	            "columns": [
	                {
	                   data: null,
	                   render: function (data, type, row, meta) {
	                       return meta.row + meta.settings._iDisplayStart + 1;
	                   },
	                   "width": "20px",
	                   "orderable": false,
	                },
	                {
	                    "data": "product_name",
	                    "orderable": true,
	                },
	                {
                        "data": "file_path",
                        render: function (data, type, row){
                            return '<img class"img-responsive img-thumbnail" src="{{ asset('storage/') }}' + "/" + data +' " style="height:100px; width:150px;">';
                        },
                        "orderable": false,
                    },
                    {
                        "data": "price",
                        render: function (data, type, row){
                            return 'Rp. ' + data;
                        },
                        "orderable": true,
                    },
	                {
	                    "data": "quantity",
	                    "orderable": true,
	                },
                    {
                        "data": "total_price",
                        render: function (data, type, row){
                            return 'Rp. ' + data;
                        },
                        "orderable": true,
                    },
	                {
	                    render : function(data, type, row){
	                        return	'<a href="#" class="edit-btn btn btn-xs btn-warning"><i class="fa fa-pencil"> Ubah</i></a>&nbsp' +
                                	'<a href="#" class="delete-btn btn btn-xs btn-danger"><i class="fa fa-trash"></i> Hapus</a>';
	                    },
	                    "width": "10%",
	                    "orderable": false,
	                }
	            ],
	            "order": [ 1, 'asc' ],
	            "fnCreatedRow" : function(nRow, aData, iDataIndex) {
	                $(nRow).attr('data', JSON.stringify(aData));
	            }
	        });

            var url;

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
                            table.draw();

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

	        // Edit
            $('#cart_table').on('click', '.edit-btn', function(e){
                $('#formCart div.form-group').removeClass('has-error');
                $('#formCart .help-block').empty();
                $('#formCart .modal-title').text("Ubah Kuantitas");
                $('#formCart')[0].reset();
                var aData = JSON.parse($(this).parent().parent().attr('data'));
                $('#formCart button[type=submit]').button('reset');

                $('#formCart .modal-body .form-horizontal').append('<input type="hidden" name="_method" value="PUT">');
                url = '{{ route("member.cart.index") }}' + '/' + aData.id;

                $('#formCart #id').val(aData.id);
                $('#formCart #quantity').val(aData.quantity);

                $('#cartModal').modal('show');
            });

            $('#formCart').submit(function (event) {
                event.preventDefault();
                $('#formCart button[type=submit]').button('loading');
                $('#formCart div.form-group').removeClass('has-error');
                $('#formCart .help-block').empty();

                var _data = $("#formCart").serialize();
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: _data,
                    dataType: 'json',
                    cache: false,

                    success: function (response) {
                        if (response.success) {
                            table.draw();

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
                        $('#formCart button[type=submit]').button('reset');
                    },

                    error: function(response){
                        if (response.status === 422) {
                            // form validation errors fired up
                            var error = response.responseJSON.errors;
                            var data = $('#formCart').serializeArray();
                            $.each(data, function(key, value){
                                console.log(data[key].name);

                                if( error[data[key].name] != undefined ){
                                    var elem = $("#formCart input[name='" + data[key].name + "']").length ? $("#formCart input[name='" + data[key].name + "']") : $("#formCart select[name='" + data[key].name + "']");
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
                        $('#formCart button[type=submit]').button('reset');
                    }
                });
            });

			// Delete
            $('#cart_table').on('click', '.delete-btn' , function(e){
                var aData = JSON.parse($(this).parent().parent().attr('data'));
                url =  $('#formDeleteCart').attr('action').replace('#', aData.id);
                $('#deleteCartModal').modal('show');
            });

            $('#formDeleteCart').submit(function (event) {
                event.preventDefault();               

                $('#deleteCartModal button[type=submit]').button('loading');
                var _data = $("#formDeleteCart").serialize();

                $.ajax({
                    url: url,
                    type: 'DELETE',
                    data: _data,
                    dataType: 'json',
                    cache: false,
                    
                    success: function (response) {
                        if (response.success) {
                            table.draw();
                                
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

                            $('#deleteCartModal').modal('toggle');
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
                        $('#deleteCartModal button[type=submit]').button('reset');
                        $('#formDeleteCart')[0].reset();
                    },
                    error: function(response){
                        if (response.status === 400 || response.status === 422) {
                            // Bad Client Request
                            $.toast({
                                heading: 'Error',
                                text : response.responseJSON.message,
                                position : 'top-right',
                                allowToastClose : true,
                                showHideTransition : 'fade',
                                icon : 'error',
                                loader : false
                            });
                        } else {
                            $.toast({
                                heading: 'Error',
                                text : "Whoops, looks like something went wrong.",
                                position : 'top-right',
                                allowToastClose : true,
                                showHideTransition : 'fade',
                                icon : 'error',
                                loader : false
                            });
                        }

                        $('#formDeleteCart button[type=submit]').button('reset');
                    }
                });
            });
	    });
	</script>
@endsection