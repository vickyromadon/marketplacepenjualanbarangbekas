@extends('layouts.app')

@section('header')
	<h1>
	Tambah Permintaan
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Permintaan</li>
	</ol>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-5">
			<div class="box box-danger">
				<div class="box-header with-border">
					<h3 class="box-title">
						Form Permintaan
					</h3>
				</div>
				<form method="POST" id="formRequest" autocomplete="off">
					<div class="box-body">
						<div class="form-group">
							<label control-label">Berikan judul untuk permintaan anda ini</label>
							<input type="text" name="title" id="title" class="form-control">
							
							<span class="help-block"></span>
						</div>

						<div class="form-group">
							<label control-label">Pilih kategori kreasi yang inginkan dibawah ini</label>
							<select name="category" id="category" class="form-control">
								<option value="">-- Pilih Salah Satu --</option>
								@foreach($categories as $category)
									<option value="{{ $category->id }}">{{ $category->name }}</option>
								@endforeach
							</select>
							
							<span class="help-block"></span>
						</div>

						<div class="form-group">
							<label control-label">Masukkan angka berapa banyak kerajinan yang mau dibuat</label>
							<input type="number" name="quantity" id="quantity" class="form-control" min="0">
							
							<span class="help-block"></span>
						</div>

						<div class="form-group">
							<label control-label">Isikan deskripsi kreasi yang anda inginkan di bawah ini</label>
							<textarea name="description" id="description" rows="10" class="form-control"></textarea>
							
							<span class="help-block"></span>
						</div>

						<div class="form-group">
		                	<label class="control-label">
		                        Jika ada gambar yang dapat membantu pengrajin, silahkan unggah dibawah ini
		                    </label><br>

		                    <div class="fileinput fileinput-new" data-provides="fileinput">
		                        <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
		                            <img id="img-upload" src="{{ asset('images/default.jpg') }}" style="width: 200px; height: 150px;">
		                        </div>
		                        <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
		                        <div>
		                            <span class="btn btn-default btn-file">
		                                <span class="fileinput-new">Pilih Gambar</span>
		                                <span class="fileinput-exists">Rubah</span>
		                                <input type="file" id="file" name="file">
		                            </span>
		                            <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">
		                                Hapus
		                            </a>
		                        </div>
		                        <span class="help-block"></span>
		                    </div>
		                </div>    
					</div>
					<div class="box-footer">
			        	<button type="reset" class="btn btn-warning pull-left" data-dismiss="modal">
		                    Reset
		                </button>
		                <button type="submit" class="btn btn-primary pull-right" data-loading-text="<i class='fa fa-spinner fa-spin'></i>">
		                	Submit
		                </button>
			        </div>
				</form>
			</div>
		</div>
		<div class="col-md-7">
			<div class="box box-danger">
				<div class="box-header with-border">
					<h3 class="box-title">
						Daftar Permintaan
					</h3>
				</div>
				<div class="box-body">						
					<table class="table table-bordered">
						<thead>
							<thead>
								<th>No</th>
								<th>Tanggal Permintaan</th>
								<th>Total Penawar</th>
								<th>Kategori</th>
								<th>Status</th>
								<th>Aksi</th>
							</thead>
						</thead>
						<tbody>
							@php
								$i = 0;
							@endphp
							@foreach($requests as $request)
								<tr>
									<td>{{ $i+=1 }}</td>
									<td>{{ $request->created_at }}</td>
									<td>{{ $request->bidder }}</td>
									<td>{{ $request->category->name }}</td>
									<td>{{ $request->status }}</td>
									<td>
										<a href="{{ route('member.request.index') }}/{{$request->id}}" class="btn btn-primary btn-sm">
											<i class="fa fa-eye"></i>
											Lihat
										</a>
										<a href="" class="btn btn-danger btn-sm">
											<i class="fa fa-trash"></i>
											Hapus
										</a>
									</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('css')
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css">
@endsection

@section('js')
	<script src="https://cdn.ckeditor.com/4.9.1/standard/ckeditor.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js"></script>
	
	<script type="text/javascript">
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

	        $('#formRequest').submit(function (event) {
                $('#formRequest div.form-group').removeClass('has-error');
                $('#formRequest .help-block').empty();
        		event.preventDefault();
                $('#formRequest button[type=submit]').button('loading');
        		CKEDITOR.instances['description'].updateElement();

        		var formData = new FormData($("#formRequest")[0]);

        		$.ajax({
                    url: '{{ route("member.request.index") }}',
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

                        $('#formRequest button[type=submit]').button('reset');
                    },

                    error: function(response){
                        if (response.status === 422) {
                            // form validation errors fired up
                            var error = response.responseJSON.errors;
                            var data = $('#formRequest').serializeArray();
                            $.each(data, function(key, value){
                                if( error[data[key].name] != undefined ){
                                    var elem;
                                    if( $("#formRequest input[name='" + data[key].name + "']").length )
                                    	elem = $("#formRequest input[name='" + data[key].name + "']");
                                    else if( $("#formRequest select[name='" + data[key].name + "']").length )
                                    	elem = $("#formRequest select[name='" + data[key].name + "']");
                                    else
                                    	elem = $("#formRequest textarea[name='" + data[key].name + "']");
                                    
                                    elem.parent().find('.help-block').text(error[data[key].name]);
                                    elem.parent().find('.help-block').show();
                                    elem.parent().addClass('has-error');
                                }
                            });
                            if(error['file'] != undefined){
                                $("#formRequest input[name='file']").parent().parent().parent().find('.help-block').text(error['file']);
                                $("#formRequest input[name='file']").parent().parent().parent().find('.help-block').show();
                                $("#formRequest input[name='file']").parent().parent().parent().parent().addClass('has-error');
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