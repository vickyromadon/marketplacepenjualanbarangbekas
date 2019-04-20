@extends('layouts.owner')

@section('header')
	<h1>Daftar Produk</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Produk</li>
	</ol>
@endsection

@section('content')
	<div class="box box-primary">
        <div class="box-header with-border">
        	<h3 class="box-title"><i class="fa fa-cubes"></i> Product</h3>
        	<button id="btnAdd" class="pull-right btn btn-primary"><i class="fa fa-plus"></i> Tambah</button>
        </div>
       
        <div class="box-body">
            <div class="table-responsive">
                <table id="product_table" class="table table-striped table-bordered table-hover nowrap dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Produk</th>
                            <th>Kuantitas</th>
                            <th>Melihat</th>
                            <th>Tanggal Membuat</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- add and edit -->
    <div class="modal fade" tabindex="-1" role="dialog" id="productModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="formProduct" autocomplete="off">
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
                                <label class="col-sm-3 control-label">Kategori</label>
                                
                                <div class="col-md-9">
                                    <select class="form-control" id="category" name="category">
                                        <option value="">-- Pilih Salah Satu --</option>
                                        @foreach ($data as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach                        
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Nama Produk</label>
                                
                                <div class="col-sm-9">
                                    <input class="form-control" type="text" id="name" name="name" placeholder="Nama Produk">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Kuantitas</label>
                                
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" id="quantity" name="quantity" min="0" placeholder="Kuantitas Produk">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Harga</label>
                                
                                <div class="col-sm-9">
                                    <input type="number" class="form-control" id="price" name="price" min="0" placeholder="Harga Produk">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">
                                    Gambar Produk
                                </label>
                                
                                <div class="col-sm-9 fileinput fileinput-new" data-provides="fileinput">
                                    <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                        <img id="img-upload" src="{{ asset('images/default.jpg') }}" style="width: 200px; height: 150px;">
                                    </div>
                                    <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
                                    <div>
                                        <span class="btn btn-default btn-file">
                                            <span class="fileinput-new">Pilih Gambar</span>
                                            <span class="fileinput-exists">Rubah</span>
                                            <input type="file" id="file_id" name="file_id">
                                        </span>
                                        <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">
                                            Hapus
                                        </a>
                                    </div>
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Deskripsi</label>
                                
                                <div class="col-sm-9">
                                    <textarea name="description" id="description" rows="10" class="form-control" placeholder="Deskripsi"></textarea>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Status</label>
                                
                                <div class="col-md-9">
                                    <select class="form-control" id="status" name="status">
                                        <option value="">-- Pilih Salah Satu --</option>
                                        <option value="publish">Publish</option>
                                        <option value="unpublish">Unpublish</option>                       
                                    </select>
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
    <div class="modal fade" tabindex="-1" role="dialog" id="deleteProductModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('owner.product.destroy', ['category' => '#']) }}" method="post" id="formDeleteProduct">
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
                        <button type="submit" class="btn btn-primary" data-loading-text="<i class='fa fa-spinner fa-spin'></i>">Ya
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('css')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css">
@endsection

@section('js')
    <script src="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.9.1/standard/ckeditor.js"></script>

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
                    "url": "{{ route('owner.product.index') }}",
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
                        "data": "name",
                        "orderable": true,
                    },
                    {
                        "data": "quantity",
                        "orderable": true,
                    },
                    {
                        "data": "view",
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
                        render : function(data, type, row){
                            if( row.status == '{{ \App\Models\Product::STATUS_PUBLISH }}' )
                                return '<span class="label label-success"><i class="fa fa-check-square"></i> '+ row.status +'</span>';
                            else if( row.status == '{{ \App\Models\Product::STATUS_UNPUBLISH }}' )
                                return '<span class="label label-warning"><i class="fa fa-minus-square"></i> '+ row.status +'</span>';
                            else
                                return '<span class="label label-danger"><i class="fa fa-close"></i> '+ row.status +'</span>';
                        },
                        "orderable": false,
                    },
                    {
                        render : function(data, type, row){
                            return '<a href="{{ route('owner.product.index') }}/'+ row.id +'" class="view-btn btn btn-xs btn-primary"><i class="fa fa-eye"> Lihat</i></a> &nbsp' +
                                '<a href="#" class="edit-btn btn btn-xs btn-warning"><i class="fa fa-pencil"> Ubah</i></a> &nbsp' + 
                                '<a href="#" class="delete-btn btn btn-xs btn-danger"><i class="fa fa-trash"></i> Hapus</a>';
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

            // add
            $('#btnAdd').click(function () {
                $('#formProduct')[0].reset();
                $('#formProduct .modal-title').text("Tambah Produk");
                $('#formProduct div.form-group').removeClass('has-error');
                $('#formProduct .help-block').empty();
                $('#formProduct button[type=submit]').button('reset');

                $('#formProduct input[name="_method"]').remove();
                url = '{{ route("owner.product.store") }}';

                CKEDITOR.instances['description'].setData('');
                $('#formProduct #img-upload').attr('src', "{{ asset('images/default.jpg') }}");

                $('#productModal').modal('show');
            });

            // Edit
            $('#product_table').on('click', '.edit-btn', function(e){
                $('#formProduct div.form-group').removeClass('has-error');
                $('#formProduct .help-block').empty();
                $('#formProduct .modal-title').text("Ubah Produk");
                $('#formProduct')[0].reset();
                var aData = JSON.parse($(this).parent().parent().attr('data'));
                $('#formProduct button[type=submit]').button('reset');

                $('#formProduct .modal-body .form-horizontal').append('<input type="hidden" name="_method" value="PUT">');
                url = '{{ route("owner.product.index") }}' + '/' + aData.id;

                $('#formProduct #id').val(aData.id);
                $('#formProduct #category').val(aData.category_id);
                $('#formProduct #name').val(aData.name);
                $('#formProduct #description').val(aData.description);
                $('#formProduct #quantity').val(aData.quantity);
                $('#formProduct #price').val(aData.price);
                CKEDITOR.instances['description'].setData(aData.description);
                $('#formProduct #img-upload').attr('src', "{{ asset('storage') }}" + '/' + aData.file.path);
                $('#formProduct #status').val(aData.status);

                $('#productModal').modal('show');
            });

            $('#formProduct').submit(function (event) {
                event.preventDefault();
                $('#formProduct div.form-group').removeClass('has-error');
                $('#formProduct .help-block').empty();
                $('#formProduct button[type=submit]').button('loading');
                CKEDITOR.instances['description'].updateElement();

                var formData = new FormData($("#formProduct")[0]);

                $.ajax({
                    url: '{{ route("owner.product.store") }}',
                    type: 'POST',
                    data: formData,
                    processData : false,
                    contentType : false,   
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

                            $('#productModal').modal('hide');
                        }
                        else {
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

                        $('#formProduct button[type=submit]').button('reset');
                    },

                    error: function(response){
                        if (response.status === 422) {
                            // form validation errors fired up
                            var error = response.responseJSON.errors;
                            var data = $('#formProduct').serializeArray();
                            $.each(data, function(key, value){
                                if( error[data[key].name] != undefined ){
                                    var elem;
                                    if( $("#formProduct input[name='" + data[key].name + "']").length )
                                        elem = $("#formProduct input[name='" + data[key].name + "']");
                                    else if( $("#formProduct select[name='" + data[key].name + "']").length )
                                        elem = $("#formProduct select[name='" + data[key].name + "']");
                                    else
                                        elem = $("#formProduct textarea[name='" + data[key].name + "']");
                                    
                                    elem.parent().find('.help-block').text(error[data[key].name]);
                                    elem.parent().find('.help-block').show();
                                    elem.parent().parent().addClass('has-error');
                                }
                            });
                            if(error['file_id'] != undefined){
                                $("#formProduct input[name='file_id']").parent().parent().parent().find('.help-block').text(error['file_id']);
                                $("#formProduct input[name='file_id']").parent().parent().parent().find('.help-block').show();
                                $("#formProduct input[name='file_id']").parent().parent().parent().parent().addClass('has-error');
                            }
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
                        $('#formProduct button[type=submit]').button('reset');
                    }
                });
            });

            // Delete
            $('#product_table').on('click', '.delete-btn' , function(e){
                var aData = JSON.parse($(this).parent().parent().attr('data'));
                url =  $('#formDeleteProduct').attr('action').replace('#', aData.id);
                $('#deleteProductModal').modal('show');
            });

            $('#formDeleteProduct').submit(function (event) {
                event.preventDefault();               

                $('#deleteProductModal button[type=submit]').button('loading');
                var _data = $("#formDeleteProduct").serialize();

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

                            $('#deleteProductModal').modal('toggle');
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
                        $('#deleteProductModal button[type=submit]').button('reset');
                        $('#formDeleteProduct')[0].reset();
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

                        $('#formDeleteProduct button[type=submit]').button('reset');
                    }
                });
            });
        });
    </script>

    <script>
        $(function () {
            CKEDITOR.replace('description', {
                toolbarGroups: [
                    { name: 'document',    groups: [ 'mode', 'document' ] },           
                    { name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
                    { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                    { name: 'links' },
                    { name: 'styles', groups: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
                    { name: 'colors', groups: [ 'TextColor', 'BGColor' ] },
                    { name: 'tools', groups: [ 'Maximize', 'ShowBlocks' ] },
                ]
            });
        });
    </script>
@endsection