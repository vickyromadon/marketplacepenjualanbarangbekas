@extends('layouts.admin')

@section('header')
	<h1>Daftar Pengembalian Dana Permintaan</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Pengembalian Dana Permintaan</li>
	</ol>
@endsection

@section('content')
	<div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-cubes"></i> Pengembalian Dana Permintaan</h3>
            <form>
                <div class="row">
                    <div class="form-group col-md-6">
                        <span class="form-group-addon"><b>&nbsp;</b></span>
                        <select class="form-control" id="status" name="status">
                            <option value="">Semua Status</option>
                            <option value="{{ \App\Models\Refund::STATUS_PENDING }}">Belum Selesai</option>
                            <option value="{{ \App\Models\Refund::STATUS_FINISH }}">Selesai</option>
                        </select>
                    </div>
                    <div class="form-group col-md-6 align-bottom">
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
                            <th>Nama Pengrajin</th>
                            <th>Harga</th>
                            <th>Status</th>
                            <th>Tanggal Membuat</th>
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

            var table = $('#refund_table').DataTable({
                "bFilter": true,
                "processing": true,
                "serverSide": true,
                "lengthChange": true,
                "ajax": {
                    "url": "{{ route('admin.refund_request.index') }}",
                    "type": "POST",
                    "data" : function(d){
                        return $.extend({},d,{
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
                        "data": "name",
                        "orderable": true,
                    },
                    {
                        "data": "price",
                        "orderable": true,
                    },
                    {
                        render : function(data, type, row){
                            if( row.status == '{{ \App\Models\Refund::STATUS_PENDING }}' )
                                return '<span class="label label-danger">Belum Selesai</span>';
                            else
                                return '<span class="label label-success">Selesai</span>';
                        },
                        "orderable": false,  
                    },
                    {
                        "data": "created_at",
                        render: function (data, type, row){
                            return moment(data, "YYYY-MM-DD").format("dddd, DD MMM YYYY");
                        },
                        "orderable": true,
                    },
                    {
                        render : function(data, type, row){
                            return  '<a href="{{ route('admin.refund_request.index') }}/'+ row.id +'" class="view-btn btn btn-xs btn-primary"><i class="fa fa-eye"> view</i></a>';
                        },
                        "width": "10%",
                        "orderable": false,
                    }
                ],
                "order": [ 4, 'dsc' ],
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