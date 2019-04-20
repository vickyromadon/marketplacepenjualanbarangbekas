@extends('layouts.owner')

@section('header')
	<h1>Penglolaan Poin</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Pengelolaan Poin</li>
	</ol>
@endsection

@section('content')
	<div class="box box-primary">
        <div class="box-header with-border">
        	<h3 class="box-title"><i class="fa fa-dot-circle-o"></i> Jumlah Poin : {{ Auth::user()->poin }}</h3>
            <button id="btnAdd" class="pull-right btn btn-primary"><i class="fa fa-plus"></i> Tambah</button>
        </div>
       
        <div class="box-body">
            <div class="table-responsive">
                <table id="bank_table" class="table table-striped table-bordered table-hover nowrap dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kresi</th>
                            <th>Tanggal Mulai</th>
                            <th>Tanggal Kadarluasa</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- add and edit -->
    <div class="modal fade" tabindex="-1" role="dialog" id="usePoinModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="formUsePoin" enctype="multipart/form-data" autocomplete="off">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title"></h4>
                    </div>

                    <div class="modal-body">
                        <div class="form-horizontal">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Nama Produk</label>
                                
                                <div class="col-sm-9">
                                    <select name="product" id="product" class="form-control">
                                    	<option value="">-- Pilih Salah Satu --</option>
                                    	@foreach( $products as $product )
                                    		<option value="{{ $product->id }}">{{ $product->name }}</option>
                                    	@endforeach
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <p>Untuk satu kreasi yang di iklankan perlu menggunakan 2 Poin dan iklan tersebut hanya berlaku untuk 2 hari.</p>                
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

	        var table = $('#bank_table').DataTable({
                "bFilter": false,
                "processing": true,
                "serverSide": true,
                "lengthChange": true,
                "ajax": {
                    "url": "{{ route('owner.use_poin.index') }}",
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
                        "data": "product.name",
                        "orderable": true,
                    },
                    {
                        "data": "created_at",
                        render: function (data, type, row){
                            return moment(data, "YYYY-MM-DD").format("DD MMM YYYY");
                        },
                        "orderable": true,
                    },
                    {
                        "data": "expired_date",
                        render: function (data, type, row){
                            return moment(data, "DD-MM-YYYY").format("DD MMM YYYY");
                        },
                        "orderable": true,
                    },
                    {
                        "data": "status",
                        "orderable": true,
                    },
                ],
                "order": [ 1, 'asc' ],
                "fnCreatedRow" : function(nRow, aData, iDataIndex) {
                    $(nRow).attr('data', JSON.stringify(aData));
                }
            });

	        var url;

            // add
            $('#btnAdd').click(function () {
                $('#formUsePoin')[0].reset();
                $('#formUsePoin .modal-title').text("Gunakan Poin");
                $('#formUsePoin div.form-group').removeClass('has-error');
                $('#formUsePoin .help-block').empty();
                $('#formUsePoin button[type=submit]').button('reset');

                $('#formUsePoin input[name="_method"]').remove();
                url = '{{ route("owner.use_poin.store") }}';

                $('#usePoinModal').modal('show');
            });

            $('#formUsePoin').submit(function (event) {
                event.preventDefault();
                $('#formUsePoin button[type=submit]').button('loading');
                $('#formUsePoin div.form-group').removeClass('has-error');
                $('#formUsePoin .help-block').empty();

                var _data = $("#formUsePoin").serialize();
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

	                        setTimeout(function () { 
		                        location.reload();
		                    }, 2000);

                            $('#usePoinModal').modal('hide');
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
                        $('#formUsePoin button[type=submit]').button('reset');
                    },

                    error: function(response){
                        if (response.status === 422) {
                            // form validation errors fired up
                            var error = response.responseJSON.errors;
                            var data = $('#formUsePoin').serializeArray();
                            $.each(data, function(key, value){
                                console.log(data[key].name);

                                if( error[data[key].name] != undefined ){
                                    var elem = $("#formUsePoin input[name='" + data[key].name + "']").length ? $("#formUsePoin input[name='" + data[key].name + "']") : $("#formUsePoin select[name='" + data[key].name + "']");
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
                        $('#formUsePoin button[type=submit]').button('reset');
                    }
                });
            });
        });
    </script>
@endsection