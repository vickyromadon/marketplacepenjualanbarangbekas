@extends('layouts.owner')

@section('header')
	<h1>Forum {{ $forum->title }}</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li>Forum</li>
		<li class="active">Forum {{ $forum->title }}</li>
	</ol>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-12">
			<div class="box box-primary">
				<div class="box-header with-border">
					<h3 class="box-title">
						Forum {{ $forum->title }}
					</h3>
				</div>
				<div class="box-body chat" id="chat-box">
					<div class="row">
						<div class="col-md-12">
							<!-- chat item -->
							<div class="item">
								@if( $forum->user->file == null )
									<img src="{{ asset('images/avatar_owner.png') }}" class="online">
								@else
									<img src="{{ asset('storage/'. $forum->user->file->path)}}" class="online">
								@endif
								<p class="message">
									<a href="#" class="name">
										<small class="text-muted pull-right">
											<i class="fa fa-clock-o"></i> 
											{{ date_format($forum->created_at,"d F Y - H:i") }}
										</small>
										{{ $forum->user->name }}
									</a>
									{{ $forum->description }}
								</p>
							</div>
							
							@foreach( $forum->forum_users as $item )
								<div class="item" style="padding-left: 5%;">
					                @if( $item->user->file == null )
										<img src="{{ asset('images/avatar_owner.png') }}" class="offline">
									@else
										<img src="{{ asset('storage/'. $item->user->file->path)}}" class="offline">
									@endif

					                <p class="message">
					                  	<a href="#" class="name">
					                    	<small class="text-muted pull-right">
					                    		<i class="fa fa-clock-o"></i> 
					                    		{{ date_format($item->created_at,"d F Y - H:i") }}
					                    	</small>
					                    	{{ $item->user->name }}
					                 	</a>
						                {{ $item->description }}
				                	</p>
				              	</div>
							@endforeach

						</div>
					</div>
				</div>
				<div class="box-footer">
					<form action="#" id="formSend" autocomplete="off">
						<div class="form-group">
							<div class="input-group">
								<input type="hidden" name="id_forum" id="id_forum" value="{{ $forum->id }}">
								<input class="form-control" name="description" id="description" placeholder="Ketik Pesan...">

								<div class="input-group-btn">
									<button type="submit" class="btn btn-success">Kirim</button>
								</div>
							</div>
						</div>
					</form>
				</div>
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

	        $('#formSend').submit(function (event) {
                event.preventDefault();
                $('#formSend div.form-group').removeClass('has-error');
                $('#formSend .help-block').empty();
                $('#formSend button[type=submit]').button('loading');

                var formData = new FormData($("#formSend")[0]);

                $.ajax({
                    url: '{{ route("owner.forum_user.store") }}',
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

                        $('#formSend button[type=submit]').button('reset');
                    },

                    error: function(response){
                        if (response.status === 422) {
                            $.toast({
                                heading: 'Error',
                                text : response.responseJSON.errors['description'][0],
                                position : 'top-right',
                                allowToastClose : true,
                                showHideTransition : 'fade',
                                icon : 'error',
                                loader : false,
                                hideAfter: 5000
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
                        $('#formSend button[type=submit]').button('reset');
                    }
                });
            });
        });
    </script>
@endsection