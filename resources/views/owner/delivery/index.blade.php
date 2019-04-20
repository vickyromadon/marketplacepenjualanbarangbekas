@extends('layouts.owner')

@section('header')
	<h1>Daftar Pengiriman</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Pengiriman</li>
	</ol>
@endsection

@section('content')
	<div class="box box-primary">
        <div class="box-header with-border">
        	<h3 class="box-title"><i class="fa fa-truck"></i> Pengiriman</h3>
        </div>
       
        <div class="box-body">
            <div class="table-responsive">
                <table id="delivery_table" class="table table-striped table-bordered table-hover nowrap dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Kuantitas</th>
                            <th>Tanggal Pesanan</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
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

            var table = $('#delivery_table').DataTable({
                "bFilter": true,
                "processing": true,
                "serverSide": true,
                "lengthChange": true,
                "ajax": {
                    "url": "{{ route('owner.delivery.index') }}",
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
                        "data": "quantity",
                        "orderable": true,
                    },
                    {
	                    "data": "created_at",
                        render: function (data, type, row){
                            return moment(data, "YYYY-MM-DD").format("dddd, DD MMM YYYY");
                        },
                        "orderable": true,
	                },
                    {
                        "data": "status",
                        "orderable": true,
                    },
                    {
                        render : function(data, type, row){
                            return	'<a href="{{ route('owner.delivery.index') }}/'+ row.id +'" class="view-btn btn btn-xs btn-primary"><i class="fa fa-eye"> Lihat</i></a>';
                        },
                        "width": "10%",
                        "orderable": false,
                    }
                ],
                "order": [ 3, 'dsc' ],
                "fnCreatedRow" : function(nRow, aData, iDataIndex) {
                    $(nRow).attr('data', JSON.stringify(aData));
                }
            });
        });
    </script>
@endsection