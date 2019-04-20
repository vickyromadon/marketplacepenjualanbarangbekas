@extends('layouts.admin')

@section('header')
	<h1>Daftar Bank</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Bank</li>
	</ol>
@endsection

@section('content')
	<div class="box box-success">
        <div class="box-header with-border">
        	<h3 class="box-title"><i class="fa fa-bank"></i> Bank</h3>
            <button id="btnAdd" class="pull-right btn btn-primary"><i class="fa fa-plus"></i> Tambah</button>
        </div>

        <div class="box-body">
            <div class="table-responsive">
                <table id="bank_table" class="table table-striped table-bordered table-hover nowrap dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Bank</th>
                            <th>Nama Akun</th>
                            <th>Nomor Akun</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>

    <!-- add and edit -->
    <div class="modal fade" tabindex="-1" role="dialog" id="bankModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="formBank" enctype="multipart/form-data" autocomplete="off">
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
                                <label class="col-sm-3 control-label">Nama Bank</label>

                                <div class="col-sm-9">
                                    <select name="name" id="name" class="form-control">
                                    	<option value="">-- Pilih Salah Satu --</option>
                                        <option value="BNI">BNI</option>
                                        <option value="BRI">BRI</option>
                                        <option value="Mandiri">Mandiri</option>
                                        <option value="BCA">BCA</option>
                                        <option value="Maybank">Maybank</option>
                                        <option value="Syariah Mandiri">Syariah Mandiri</option>
                                        <option value="SUMUT">SUMUT</option>
                                        <option value="BTPN">BTPN</option>
                                        <option value="CIMB Niaga">CIMB Niaga</option>
                                        <option value="Sinarmas">Sinarmas</option>
                                        <option value="Mega">Mega</option>
                                    </select>
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Nomor Akun</label>

                                <div class="col-sm-9">
                                    <input type="text" id="number" name="number" class="form-control" placeholder="Account Number">
                                    <span class="help-block"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Nama Akun</label>

                                <div class="col-sm-9">
                                    <input type="text" id="owner" name="owner" class="form-control" placeholder="Account Name">
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
    <div class="modal fade" tabindex="-1" role="dialog" id="deleteBankModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.bank.destroy', ['bank' => '#']) }}" method="post" id="formDeleteBank">
                	{{ method_field('DELETE') }}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title">Hapus Bank</h4>
                    </div>

                    <div class="modal-body">
                        <p id="del-success">Anda yakin ingin menghapus Bank ?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                            Tidak
                        </button>
                        <button type="submit" class="btn btn-primary" data-loading-text="<i class='fa fa-spinner fa-spin'></i>">	Ya
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
                "bFilter": true,
                "processing": true,
                "serverSide": true,
                "lengthChange": true,
                "ajax": {
                    "url": "{{ route('admin.bank.index') }}",
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
                        "data": "owner",
                        "orderable": true,
                    },
                    {
                        "data": "number",
                        "orderable": true,
                    },
                    {
                        render : function(data, type, row){
                            return	'<a href="#" class="edit-btn btn btn-xs btn-warning"><i class="fa fa-pencil"> Ubah</i></a> &nbsp' +
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

            var url;

            // add
            $('#btnAdd').click(function () {
                $('#formBank')[0].reset();
                $('#formBank .modal-title').text("Add Bank");
                $('#formBank div.form-group').removeClass('has-error');
                $('#formBank .help-block').empty();
                $('#formBank button[type=submit]').button('reset');

                $('#formBank input[name="_method"]').remove();
                url = '{{ route("admin.bank.store") }}';

                $('#bankModal').modal('show');
            });

            // Edit
            $('#bank_table').on('click', '.edit-btn', function(e){

                $('#formBank div.form-group').removeClass('has-error');
                $('#formBank .help-block').empty();
                $('#formBank .modal-title').text("Edit Bank");
                $('#formBank')[0].reset();
                var aData = JSON.parse($(this).parent().parent().attr('data'));
                $('#formBank button[type=submit]').button('reset');

                $('#formBank .modal-body .form-horizontal').append('<input type="hidden" name="_method" value="PUT">');
                url = '{{ route("admin.bank.index") }}' + '/' + aData.id;

                $('#id').val(aData.id);
                $('#name').val(aData.name);
                $('#number').val(aData.number);
                $('#owner').val(aData.owner);

                $('#bankModal').modal('show');
            });

            $('#formBank').submit(function (event) {
                event.preventDefault();
                $('#formBank button[type=submit]').button('loading');
                $('#formBank div.form-group').removeClass('has-error');
                $('#formBank .help-block').empty();

                var _data = $("#formBank").serialize();
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

                            $('#bankModal').modal('hide');
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
                        $('#formBank button[type=submit]').button('reset');
                    },

                    error: function(response){
                        if (response.status === 422) {
                            // form validation errors fired up
                            var error = response.responseJSON.errors;
                            var data = $('#formBank').serializeArray();
                            $.each(data, function(key, value){
                                console.log(data[key].name);

                                if( error[data[key].name] != undefined ){
                                    var elem = $("#formBank input[name='" + data[key].name + "']").length ? $("#formBank input[name='" + data[key].name + "']") : $("#formBank select[name='" + data[key].name + "']");
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
                        $('#formBank button[type=submit]').button('reset');
                    }
                });
            });

			// Delete
            $('#bank_table').on('click', '.delete-btn' , function(e){
                var aData = JSON.parse($(this).parent().parent().attr('data'));
                url =  $('#formDeleteBank').attr('action').replace('#', aData.id);
                $('#deleteBankModal').modal('show');
            });

            $('#formDeleteBank').submit(function (event) {
                event.preventDefault();

                $('#deleteBankModal button[type=submit]').button('loading');
                var _data = $("#formDeleteBank").serialize();

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

                            $('#deleteBankModal').modal('toggle');
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
                        $('#deleteBankModal button[type=submit]').button('reset');
                        $('#formDeleteBank')[0].reset();
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

                        $('#formDeleteBank button[type=submit]').button('reset');
                    }
                });
            });
        });
    </script>
@endsection
