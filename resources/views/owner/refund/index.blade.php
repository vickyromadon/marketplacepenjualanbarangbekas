@extends('layouts.owner')

@section('header')
	<h1>Daftar Pengembalian Dana</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Pengembalian Dana</li>
	</ol>
@endsection

@section('content')
	<div class="box box-primary">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-cubes"></i> Pengembalian Dana</h3>
            <form>
                <div class="row">
                    <div class="form-group col-md-4">
                        <span class="form-group-addon"><b>&nbsp;</b></span>
                        <select class="form-control" id="status" name="status">
                            <option value="">Semua Status</option>
                            <option value="{{ \App\Models\Refund::STATUS_PENDING }}">Belum Selesai</option>
                            <option value="{{ \App\Models\Refund::STATUS_FINISH }}">Selesai</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <span class="form-group-addon"><b>&nbsp;</b></span>
                        <select class="form-control" id="type" name="type">
                            <option value="">Semua Tipe</option>
                            <option value="{{ \App\Models\Refund::TYPE_SELL }}">Penjualan</option>
                            <option value="{{ \App\Models\Refund::TYPE_REQUEST }}">Permintaan</option>
                            <option value="{{ \App\Models\Refund::TYPE_AUCTION }}">Pelelangan</option>
                        </select>
                    </div>
                    <div class="form-group col-md-4 align-bottom">
                        <span class="form-group-addon"><b>&nbsp;</b></span>
                        <button id="btnFilter" class="form-control btn btn-md btn-primary"><i class="fa fa-filter"></i> Saring</button>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="box-body">
            <div class="table-responsive">
                <table id="refund_table" class="table table-striped table-bordered table-hover nowrap dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kreasi</th>
                            <th>Nomor Resi</th>
                            <th>Kuantitas</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th>Tipe</th>
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

            var table = $('#refund_table').DataTable({
                "bFilter": true,
                "processing": true,
                "serverSide": true,
                "lengthChange": true,
                "ajax": {
                    "url": "{{ route('owner.refund.index') }}",
                    "type": "POST",
                    "data" : function(d){
                        return $.extend({},d,{
                            'type' : $('#type').val(),
                            'status' : $('#status').val(),
                        });
                    }
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
                        "data": "number_proof",
                        "orderable": true,
                    },
                    {
                        "data": "quantity",
                        "orderable": true,
                    },
                    {
                        "data": "price",
                        "orderable": true,
                    },
                    {
                        render : function(data, type, row){
                            if( row.status == '{{ \App\Models\Refund::STATUS_PENDING }}' )
                                return '<span class="label label-danger">Belum Dikirim</span>';
                            else
                                return '<span class="label label-success">Sudah Dikirim</span>';
                        },
                        "orderable": false,  
                    },
                    {
                    	"data": "type",
                        render : function(data, type, row){
                            if( data == '{{ \App\Models\Refund::TYPE_SELL }}' )
                                return '<span class="label label-warning">Penjualan</span>';
                            else if(data == '{{ \App\Models\Refund::TYPE_REQUEST }}')
                            	return '<span class="label label-primary">Permintaan</span>';
                            else
                                return '<span class="label label-info">Pelelangan</span>';
                        },
                        "orderable": false,  
                    },
                ],
                "order": [ 1, 'dsc' ],
                "fnCreatedRow" : function(nRow, aData, iDataIndex) {
                    $(nRow).attr('data', JSON.stringify(aData));
                }
            });

            $('#btnFilter').click(function (e) {
               e.preventDefault();
               table.draw();
            });
        });
    </script>
@endsection