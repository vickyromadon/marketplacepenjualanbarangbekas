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
        
        </div>
       
        <div class="box-body">
            <div class="table-responsive">
                <form action="#" method="post" id="formArticle" autocomplete="off">
                    <div class="modal-body">
                        <div class="form-horizontal">
                            <input type="hidden" id="id" name="id">
                            <div class="form-group">
                                <label class="col-sm-3 control-label pull-left">Judul Artikel</label>
                                
                                <div class="col-sm-9">
                                    <input type="text" id="title" name="title" class="form-control" placeholder="Judul Artikel" required>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Deskripsi</label>
                                
                                <div class="col-sm-9">
                                    <textarea name="description" id="description" class="form-control" placeholder="Deskripsi">
                                    </textarea>
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

    <!-- add and edit -->
    <!-- <div class="modal fade" tabindex="-1" role="dialog" id="articleModal">
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
    </div> -->

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


            $('#formArticle').submit(function (event) {
                $('#formArticle div.form-group').removeClass('has-error');
                $('#formArticle .help-block').empty();
                event.preventDefault();
                $('#formArticle button[type=submit]').button('loading');
                CKEDITOR.instances['description'].updateElement();

                var formData = new FormData($("#formArticle")[0]);

                $.ajax({
                    url: '{{ route("admin.article.store") }}',
                    type: 'POST',
                    data: formData,
                    processData : false,
                    contentType : false,   
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

                        $('#formArticle button[type=submit]').button('reset');
                    },

                    error: function(response){
                        if (response.status === 422) {
                            // form validation errors fired up
                            var error = response.responseJSON.errors;
                            var data = $('#formArticle').serializeArray();
                            $.each(data, function(key, value){
                                if( error[data[key].name] != undefined ){
                                    var elem;
                                    if( $("#formArticle input[name='" + data[key].name + "']").length )
                                        elem = $("#formArticle input[name='" + data[key].name + "']");
                                    else if( $("#formArticle select[name='" + data[key].name + "']").length )
                                        elem = $("#formArticle select[name='" + data[key].name + "']");
                                    else
                                        elem = $("#formArticle textarea[name='" + data[key].name + "']");
                                    
                                    elem.parent().find('.help-block').text(error[data[key].name]);
                                    elem.parent().find('.help-block').show();
                                    elem.parent().addClass('has-error');
                                }
                            });
                            if(error['file'] != undefined){
                                $("#formArticle input[name='file']").parent().parent().parent().find('.help-block').text(error['file']);
                                $("#formArticle input[name='file']").parent().parent().parent().find('.help-block').show();
                                $("#formArticle input[name='file']").parent().parent().parent().parent().addClass('has-error');
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
                        $('#formRequest button[type=submit]').button('reset');
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
