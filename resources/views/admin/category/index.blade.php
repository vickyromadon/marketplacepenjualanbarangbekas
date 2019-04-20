@extends('layouts.admin')

@section('header')
	<h1>Daftar Kategori</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Kategori</li>
	</ol>
@endsection

@section('content')
	<div class="box box-success">
        <div class="box-header with-border">
        	<h3 class="box-title"><i class="fa fa-th-list"></i> Kategori</h3>
            <button id="btnAdd" class="pull-right btn btn-primary"><i class="fa fa-plus"></i> Tambah</button>
        </div>
       
        <div class="box-body">
            <div class="table-responsive">
                <table id="category_table" class="table table-striped table-bordered table-hover nowrap dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Kategori</th>
                            <th>Deskripsi</th>
                            <th>Tanggal Membuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- add and edit -->
    <div class="modal fade" tabindex="-1" role="dialog" id="categoryModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="formCategory" autocomplete="off">
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
                                <label class="col-sm-3 control-label">Nama Kategori</label>
                                
                                <div class="col-sm-9">
                                    <input type="text" id="name" name="name" class="form-control" placeholder="Nama Kategori" required>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Deskripsi</label>
                                
                                <div class="col-sm-9">
                                    <textarea name="description" id="description" class="form-control" placeholder="Deskripsi"></textarea>
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
    <div class="modal fade" tabindex="-1" role="dialog" id="deleteCategoryModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.category.destroy', ['category' => '#']) }}" method="post" id="formDeleteCategory">
                    {{ method_field('DELETE') }}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Hapus Kategori</h4>
                    </div>

                    <div class="modal-body">
                        <p id="del-success">Anda yakin ingin menghapus Kategori ?</p>
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

            var table = $('#category_table').DataTable({
                "bFilter": true,
                "processing": true,
                "serverSide": true,
                "lengthChange": true,
                "ajax": {
                    "url": "{{ route('admin.category.index') }}",
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
                        "data": "description",
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
                            return  '<a href="#" class="edit-btn btn btn-xs btn-warning"><i class="fa fa-pencil"> Ubah</i></a> &nbsp'; 
                            // +
                            //     '<a href="#" class="delete-btn btn btn-xs btn-danger"><i class="fa fa-trash"></i> Delete</a>';
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

            // add
            $('#btnAdd').click(function () {
                $('#formCategory')[0].reset();
                $('#formCategory .modal-title').text("Tambah Kategori");
                $('#formCategory div.form-group').removeClass('has-error');
                $('#formCategory .help-block').empty();
                $('#formCategory button[type=submit]').button('reset');

                $('#formCategory input[name="_method"]').remove();
                url = '{{ route("admin.category.store") }}';

                $('#categoryModal').modal('show');
            });

            // Edit
            $('#category_table').on('click', '.edit-btn', function(e){

                $('#formCategory div.form-group').removeClass('has-error');
                $('#formCategory .help-block').empty();
                $('#formCategory .modal-title').text("Ubah Kategori");
                $('#formCategory')[0].reset();
                var aData = JSON.parse($(this).parent().parent().attr('data'));
                $('#formCategory button[type=submit]').button('reset');

                $('#formCategory .modal-body .form-horizontal').append('<input type="hidden" name="_method" value="PUT">');
                url = '{{ route("admin.category.index") }}' + '/' + aData.id;

                $('#id').val(aData.id);
                $('#name').val(aData.name);
                $('#description').val(aData.description);  

                $('#categoryModal').modal('show');
            });

            $('#formCategory').submit(function (event) {
                event.preventDefault();
                $('#formCategory button[type=submit]').button('loading');
                $('#formCategory div.form-group').removeClass('has-error');
                $('#formCategory .help-block').empty();

                var _data = $("#formCategory").serialize();
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

                            $('#categoryModal').modal('hide');
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
                        $('#formCategory button[type=submit]').button('reset');
                    },

                    error: function(response){
                        if (response.status === 422) {
                            // form validation errors fired up
                            var error = response.responseJSON.errors;
                            var data = $('#formCategory').serializeArray();
                            $.each(data, function(key, value){
                                if( error[data[key].name] != undefined ){
                                    var elem = $("#formCategory input[name='" + data[key].name + "']").length ? $("#formCategory input[name='" + data[key].name + "']") : $("#formCategory textarea[name='" + data[key].name + "']");
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
                        $('#formCategory button[type=submit]').button('reset');
                    }
                });
            });

            // Delete
            $('#category_table').on('click', '.delete-btn' , function(e){
                var aData = JSON.parse($(this).parent().parent().attr('data'));
                url =  $('#formDeleteCategory').attr('action').replace('#', aData.id);
                $('#deleteCategoryModal').modal('show');
            });

            $('#formDeleteCategory').submit(function (event) {
                event.preventDefault();               

                $('#deleteCategoryModal button[type=submit]').button('loading');
                var _data = $("#formDeleteCategory").serialize();

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

                            $('#deleteCategoryModal').modal('toggle');
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
                        $('#deleteCategoryModal button[type=submit]').button('reset');
                        $('#formDeleteCategory')[0].reset();
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

                        $('#formDeleteCategory button[type=submit]').button('reset');
                    }
                });
            });
        });
    </script>
@endsection