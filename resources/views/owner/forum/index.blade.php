@extends('layouts.owner')

@section('header')
	<h1>Forum Pengrajin</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li class="active">Forum Pengrajin</li>
	</ol>
@endsection

@section('content')
	<div class="box box-primary">
        <div class="box-header with-border">
        	<h3 class="box-title"><i class="fa fa-rss"></i> Forum Pengrajin</h3>
        	<button id="btnAdd" class="pull-right btn btn-primary"><i class="fa fa-plus"></i> Mulai Forum</button>
        </div>
        <div class="box-body">
        	@foreach( $forums as $forum )
	            <div class="row">
	            	<div class="col-md-12">
	            		<div class="row">
			            	<div class="col-md-12">
			            		<p class="pull-left" style="font-size: 2em;">{{ $forum->title }}</p>
			            		<p class="pull-right"><i class="fa fa-clock-o"></i> Di Posting Pada {{ date_format($forum->created_at,"d F Y - H:i") }}</p>
			            	</div>
			            </div>
			            
			            <div class="row">
			            	<div class="col-md-12">
			            		<p>
					            	<i class="fa fa-user-o"></i>
					            	oleh : <a href="#">{{ $forum->user->name }}</a>
					            </p>
			            	</div>
			            </div>
			            
			            <div class="row">
                            <div class="col-md-12">
                                <p>
                                    {!! strlen($forum->description) > 250 ? substr($forum->description, 0, 250) . '...' : $forum->description !!}
                                </p>
                            </div>         
                        </div>

                        <div class="row">
                            @if( $forum->user_id == Auth::user()->id )
                                <div class="col-md-6">
                                    <a class="btn btn-danger pull-left" href="{{ route('owner.forum.index') }}/{{ $forum->id }}">
                                        Delete Forum <i class="fa fa-trash"></i>
                                    </a>
                                </div>
                            @endif
                            <div class="col-md-6">
                                <a class="btn btn-success pull-right" href="{{ route('owner.forum.index') }}/{{ $forum->id }}">
                                    Ikuti Forum <i class="fa fa-edit"></i>
                                </a>
                            </div>
                        </div>

	            	</div>
	            </div>
	            <hr>
        	@endforeach
        </div>
    </div>

    <!-- add and edit -->
    <div class="modal fade" tabindex="-1" role="dialog" id="forumModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="#" method="post" id="formForum" autocomplete="off">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 class="modal-title"></h4>
                    </div>

                    <div class="modal-body">
                        <div class="form-horizontal">
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Judul</label>
                                
                                <div class="col-sm-9">
                                    <input class="form-control" type="text" id="title" name="title" placeholder="Masukkan judul pertanyaan">
                                    <span class="help-block"></span>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Pertanyaan</label>
                                
                                <div class="col-sm-9">
                                    <textarea name="description" id="description" class="form-control" placeholder="Masukkan pertanyaan anda"></textarea>
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
                            Kirim
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

	        // add
            $('#btnAdd').click(function () {
                $('#formForum')[0].reset();
                $('#formForum .modal-title').text("Tambah Forum");
                $('#formForum div.form-group').removeClass('has-error');
                $('#formForum .help-block').empty();
                $('#formForum button[type=submit]').button('reset');

                url = '{{ route("owner.forum.store") }}';

                $('#forumModal').modal('show');
            });

            $('#formForum').submit(function (event) {
                event.preventDefault();
                $('#formForum div.form-group').removeClass('has-error');
                $('#formForum .help-block').empty();
                $('#formForum button[type=submit]').button('loading');

                var formData = new FormData($("#formForum")[0]);

                $.ajax({
                    url: url,
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

                            $('#forumModal').modal('hide');

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

                        $('#formForum button[type=submit]').button('reset');
                    },

                    error: function(response){
                        if (response.status === 422) {
                            // form validation errors fired up
                            var error = response.responseJSON.errors;
                            var data = $('#formForum').serializeArray();
                            $.each(data, function(key, value){
                                if( error[data[key].name] != undefined ){
                                    var elem;
                                    if( $("#formForum input[name='" + data[key].name + "']").length )
                                        elem = $("#formForum input[name='" + data[key].name + "']");
                                    else if( $("#formForum select[name='" + data[key].name + "']").length )
                                        elem = $("#formForum select[name='" + data[key].name + "']");
                                    else
                                        elem = $("#formForum textarea[name='" + data[key].name + "']");
                                    
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
                        $('#formForum button[type=submit]').button('reset');
                    }
                });
            });
        });
    </script>
@endsection
