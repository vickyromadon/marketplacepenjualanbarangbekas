@extends('layouts.admin')

@section('header')
	<h1>Daftar Artikel</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Artikel</li>
	</ol>
@endsection

@section('content')
	<div class="box box-success">
        <div class="box-header with-border">
        	<h3 class="box-title"><i class="fa fa-th-list"></i> Artikel</h3>
            <!-- <button id="btnAdd" ></button> -->
            <a href="{{ route('admin.article.store') }}" class="pull-right btn btn-primary">
                <i class="fa fa-plus"></i> Tambah 
            </a>
        </div>
       
        <div class="box-body">
            <div class="table-responsive">
                <table id="article_table" class="table table-striped table-bordered table-hover nowrap dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul Artikel</th>
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
    <div class="modal fade" tabindex="-1" role="dialog" id="articleModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="formArticle" autocomplete="off">
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
                                <label class="col-sm-3 control-label">Judul Artikel</label>
                                
                                <div class="col-sm-9">
                                    <input type="text" id="name" name="name" class="form-control" placeholder="Judul Artikel" required>
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

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Link</label>
                                
                                <div class="col-sm-9">
                                	<input type="text" id="link" name="link" class="form-control" placeholder="Link" required>
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
    <div class="modal fade" tabindex="-1" role="dialog" id="deleteArticleModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.article.destroy', ['id' => '#']) }}" method="post" id="formDeleteArticle">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Hapus Kategori</h4>
                    </div>

                    <div class="modal-body">
                        <p id="del-success">Anda yakin ingin menghapus Artikel ini ?</p>
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
			
			var table = $('#article_table').DataTable({
                "bFilter": true,
                "processing": true,
                "serverSide": true,
                "lengthChange": true,
                "ajax": {
                    "url": "{{ route('admin.article.index') }}",
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
                        "data": "title",
                        "orderable": true,
                    },
                    {
                        "data": "description",
                        "orderable": true,
                    },
                    {
                        "data": "link",
                        "orderable": true,
                    },
                    {
                        render : function(data, type, row){
                            return  '<a href="{{ route('admin.article.index') }}/edit/'+ row.id +'" class="edit-btn btn btn-xs btn-warning"><i class="fa fa-pencil"> Ubah</i></a> &nbsp' +
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

            // Delete
            $('#article_table').on('click', '.delete-btn' , function(e){
                var aData = JSON.parse($(this).parent().parent().attr('data'));
                url =  $('#formDeleteArticle').attr('action').replace('#', aData.id);
                $('#deleteArticleModal').modal('show');
            });

            $('#formDeleteArticle').submit(function (event) {
                event.preventDefault();               

                $('#deleteArticleModal button[type=submit]').button('loading');
                var _data = $("#formDeleteArticle").serialize();

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

                            $('#deleteArticleModal').modal('toggle');
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
                        $('#deleteArticleModal button[type=submit]').button('reset');
                        $('#formDeleteArticle')[0].reset();
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

                        $('#formDeleteArticle button[type=submit]').button('reset');
                    }
                });
            });
    
    	});

    </script>
@endsection
