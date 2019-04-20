@extends('layouts.app')

@section('header')
	<h1>Riwayat Penjualan Barang Bekas</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Riwayat Penjualan Barang Bekas</li>
	</ol>
@endsection

@section('content')
	<div class="box box-danger">
        <div class="box-header with-border">
        	<h3 class="box-title"><i class="fa fa-cube"></i> Riwayat Penjualan Barang Bekas</h3>
        </div>
       
        <div class="box-body">
            <div class="table-responsive">
                <table id="buy_table" class="table table-striped table-bordered table-hover nowrap dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang Bekas</th>
                            <th>Berat</th>
                            <th>Harga Penjualan</th>
                            <th>Tanggal Menjual</th>
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

            var table = $('#buy_table').DataTable({
                "bFilter": false,
                "processing": true,
                "serverSide": true,
                "lengthChange": true,
                "ajax": {
                    "url": "{{ route('member.buy.history') }}",
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
                        "data": "category.name",
                        "orderable": false,
                    },
                    {
                        "data": "weight",
                        "orderable": true,
                    },
                    {
                        "data": "price",
                        "orderable": true,
                    },
                    {
                        "data": "created_at",
                        render: function (data, type, row){
                            return moment(data, "YYYY-MM-DD").format("dddd, DD MMM YYYY");
                        },
                        "width": "20px",
                        "orderable": true,
                    },
                    {
                        "data": "status",
                        "orderable": true,
                    },
                    {
                        render : function(data, type, row){
                            return  '<a href="#" class="view-btn btn btn-xs btn-info"><i class="fa fa-eye"> Lihat</i></a>';
                        },
                        "width": "10%",
                        "orderable": false,
                    }
                ],
                "order": [ 2, 'asc' ],
                "fnCreatedRow" : function(nRow, aData, iDataIndex) {
                    $(nRow).attr('data', JSON.stringify(aData));
                }
            });
        });
    </script>
@endsection