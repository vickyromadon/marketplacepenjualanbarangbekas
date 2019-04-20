@extends('layouts.admin')

@section('header')
	<h1>Pengaturan</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Pengaturan</li>
	</ol>
@endsection

@section('content')
	<div class="box box-success">
        <div class="box-header with-border">
        	<h3 class="box-title"><i class="fa fa-gears"></i> Pengaturan Harga</h3>
        </div>
       
        <div class="box-body">
            <div class="table-responsive">
                <table id="category_table" class="table table-striped table-bordered table-hover nowrap dataTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis</th>
                            <th>Harga Beli / (KG)</th>
                            <th>Minimal Beli / (KG)</th>
                            <th>Minimal Jual / (KG)</th>
                            <th>Harga Jual / (KG)</th>
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
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Harga Beli</label>
                                    
                                    <div class="col-sm-8">
                                        <input type="number" id="price_buy" name="price_buy" class="form-control" placeholder="Masukkan Harga Beli" min="1">
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Harga Jual</label>
                                    
                                    <div class="col-sm-8">
                                        <input type="number" id="price_sell" name="price_sell" class="form-control" placeholder="Masukkan Harga Jual" min="1">
                                        <span class="help-block"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Min. Beli</label>
                                    
                                    <div class="col-sm-8">
                                        <input type="number" id="min_buy" name="min_buy" class="form-control" placeholder="Masukkan Min. Beli" min="1">
                                        <span class="help-block"></span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-4 control-label">Min. Jual</label>
                                    
                                    <div class="col-sm-8">
                                        <input type="number" id="min_sell" name="min_sell" class="form-control" placeholder="Masukkan Min. Jual" min="1">
                                        <span class="help-block"></span>
                                    </div>
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
                    "url": "{{ route('admin.setting.index') }}",
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
                        "data": "price_buy",
                        "orderable": true,
                    },
                    {
                        "data": "min_buy",
                        "orderable": true,
                    },
                    {
                        "data": "price_sell",
                        "orderable": true,
                    },
                    {
                        "data": "min_sell",
                        "orderable": true,
                    },
                    {
                        render : function(data, type, row){
                            return  '<a href="#" class="edit-btn btn btn-xs btn-warning"><i class="fa fa-pencil"> Ubah</i></a> &nbsp';
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

            // Edit
            $('#category_table').on('click', '.edit-btn', function(e){

                var aData = JSON.parse($(this).parent().parent().attr('data'));
                $('#formCategory div.form-group').removeClass('has-error');
                $('#formCategory .help-block').empty();
                $('#formCategory .modal-title').text("Tentukan Harga " + aData.name);
                $('#formCategory')[0].reset();
                $('#formCategory button[type=submit]').button('reset');

                $('#formCategory .modal-body .form-horizontal').append('<input type="hidden" name="_method" value="PUT">');
                url = '{{ route("admin.setting.index") }}' + '/' + aData.id;

                $('#id').val(aData.id);  

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
        });
    </script>
@endsection

