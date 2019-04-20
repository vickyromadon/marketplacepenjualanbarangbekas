@extends('layouts.admin')

@section('header')
	<h1>Daftar Produk</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Produk</li>
	</ol>
@endsection

@section('content')
	<div class="box box-success">
        <div class="box-header with-border">
            <h3 class="box-title"><i class="fa fa-cubes"></i> Pengelolaan Produk</h3>
            <form>
                <div class="row">
                    <div class="form-group col-md-4">
                        <span class="form-group-addon"><b>&nbsp;</b></span>
                        <select class="form-control" id="name_category" name="name_category">
                            <option value="">Semua Kategori</option>
                            @foreach( $categories as $category )
                                <option value="{{ $category->name }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-md-4">
                        <span class="form-group-addon"><b>&nbsp;</b></span>
                        <select class="form-control" id="status" name="status">
                            <option value="">All Status</option>
                            <option value="unpublish">Unpublish</option>
                            <option value="publish">Publish</option>
                            <option value="blockir">Blockir</option>
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
                <table id="product_table" class="table table-striped table-bordered table-hover nowrap dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Pemilik</th>
                            <th>Kategori</th>
                            <th>Tanggal Membuat</th>
                            <th>Total Melihat</th>
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

            var table = $('#product_table').DataTable({
                "bFilter": true,
                "processing": true,
                "serverSide": true,
                "lengthChange": true,
                "ajax": {
                    "url": "{{ route('admin.product.index') }}",
                    "type": "POST",
                    "data" : function(d){
                        return $.extend({},d,{
                            'name_category' : $('#name_category').val(),
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
                        "data": "user_name",
                        "orderable": true,
                    },
                    {
                        "data": "category_name",
                        "orderable": true,
                    },
                    {
                        "data": "product_created_at",
                        render: function (data, type, row){
                            return moment(data, "YYYY-MM-DD").format("dddd, DD MMM YYYY");
                        },
                        "orderable": true,
                    },
                    {
                        "data": "view",
                        "orderable": true,
                    },
                    {
                        render : function(data, type, row){
                            if( row.product_status == '{{ \App\Models\Product::STATUS_PUBLISH }}' )
                                return '<span class="label label-success">'+ row.product_status +'</span>';
                            else if( row.product_status == '{{ \App\Models\Product::STATUS_UNPUBLISH }}' )
                                return '<span class="label label-warning">'+ row.product_status +'</span>';
                            else
                                return '<span class="label label-danger">'+ row.product_status +'</span>';
                        },
                        "orderable": false,  
                    },
                    {
                        render : function(data, type, row){
                            return  '<a href="{{ route('admin.product.index') }}/'+ row.id +'" class="view-btn btn btn-xs btn-primary"><i class="fa fa-eye"> view</i></a>';
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