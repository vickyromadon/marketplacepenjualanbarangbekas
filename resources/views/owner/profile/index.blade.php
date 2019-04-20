@extends('layouts.owner')

@section('header')
	<section class="content-header">
		<h1>
		Profile
		<small>Owner</small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li class="active">Profile</li>
		</ol>
	</section>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-4">
			<div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Data Profile</h3>
                </div>
				<div class="box-body box-profile">
					@if ( $data->file != null )
                        <img class="profile-user-img img-responsive" src="{{ asset('storage/'. $data->file->path)}}" alt="User profile picture">
                    @else
                        <img class="profile-user-img img-responsive img-circle" src="{{ asset('images/avatar_owner.png') }}" alt="User profile picture">
                    @endif

					<h3 class="profile-username text-center">{{ $data->name }}</h3>

					<ul class="list-group list-group-unbordered">
						<li class="list-group-item">
							<b>Email</b> <p class="pull-right">{{ $data->email }}</p>
						</li>
						<li class="list-group-item">
							<b>No. HP</b>
							<p class="pull-right">
								@if( $data->phone != null )
									{{ $data->phone }}
								@else
									-
								@endif	
							</p>
						</li>
                        <li class="list-group-item">
                            <b>Umur</b>
                            <p class="pull-right">
                                @if( $data->age != null )
                                    {{ $data->age }}
                                @else
                                    -
                                @endif  
                            </p>
                        </li>
                        <li class="list-group-item">
                            <b>Tempat Lahir</b>
                            <p class="pull-right">
                                @if( $data->birthplace != null )
                                    {{ $data->birthplace }}
                                @else
                                    -
                                @endif  
                            </p>
                        </li>
                        <li class="list-group-item">
                            <b>Tanggal Lahir</b>
                            <p class="pull-right">
                                @if( $data->birthdate != null )
                                    {!! date('d F Y', strtotime($data->birthdate)); !!}
                                @else
                                    -
                                @endif  
                            </p>
                        </li>
                        <li class="list-group-item">
                            <b>Jenis Kelamin</b>
                            <p class="pull-right">
                                @if( $data->gender != null )
                                    {{ $data->gender }}
                                @else
                                    -
                                @endif  
                            </p>
                        </li>
                        <li class="list-group-item">
                            <b>Agama</b>
                            <p class="pull-right">
                                @if( $data->religion != null )
                                    {{ $data->religion }}
                                @else
                                    -
                                @endif  
                            </p>
                        </li>
                        <li class="list-group-item">
                            <b>Poin</b>
                            <p class="pull-right">
                                {{ $data->poin }}
                            </p>
                        </li>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-md-8">
			<div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Ubah Data Profile</h3>
                </div>
				<div class="box-body">
					<form class="form-horizontal" method="POST" id="formSetting" autocomplete="off">
						<div class="form-group">
							<label class="col-sm-3 control-label">Nama</label>

							<div class="col-sm-9">
								<input type="text" class="form-control" id="name" name="name" placeholder="Nama">
								
								<span class="help-block"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-3 control-label">No. HP</label>

							<div class="col-sm-9">
								<input type="text" class="form-control" id="phone" name="phone" placeholder="No. HP">
								
								<span class="help-block"></span>
							</div>
						</div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">
                                Umur
                            </label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control" id="age" name="age" min="0" max="100" placeholder="Umur">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">
                                Tempat / Tanggal Lahir
                            </label>
                            <div class="col-sm-5">
                                <input type="text" id="birthplace" name="birthplace" class="form-control" placeholder="Tempat Lahir">

                                <span class="help-block"></span>
                            </div>
                            <div class="col-sm-4">
                                <div class="input-group date">
                                    <div class="input-group-addon">
                                        <i class="fa fa-calendar"></i>
                                    </div>
                                    <input type="text" class="form-control" name="birthdate" id="birthdate" placeholder="Tanggal Lahir">
                                    <span id="birthdate" class="help-block"></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">
                                Jenis Kelamin
                            </label>
                            <div class="col-sm-9">
                                <label class="radio-inline">
                                    <input type="radio" id="gender_lk" name="gender" value="Male" checked>Laki - Laki
                                </label>
                                <label class="radio-inline">
                                    <input type="radio" id="gender_pr" name="gender" value="Female">Perempuan
                                </label>

                                <span id="error_gender" class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">
                                Agama
                            </label>
                            <div class="col-sm-9">
                                <select class="form-control" id="religion" name="religion">
                                    <option value="">-- Pilih Salah Satu --</option>
                                    <option value="Islam">Islam</option>
                                    <option value="Kristen Protestan">Kristen Protestan</option>
                                    <option value="Katolik">Katolik</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Buddha">Buddha</option>
                                    <option value="Kong Hu Cu">Kong Hu Cu</option>
                                </select>

                                <span id="error_religion" class="help-block"></span>
                            </div>
                        </div>
						<div class="form-group">
		                    <div class="col-sm-offset-3 col-sm-9">
		                      	<button type="submit" class="btn btn-danger"><i class="fa fa-save"></i> Simpan</button>
		                    </div>
	                  	</div>
					</form>
				</div>
			</div>

			<div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Ubah Kata Sandi</h3>
                </div>
				<div class="box-body">
					<form class="form-horizontal" method="POST" id="formPassword">
						<div class="form-group">
							<label class="col-sm-4 control-label">Kata Sandi Lama</label>
							<div class="col-sm-8">
								<input type="password" class="form-control" id="current_password" name="current_password" placeholder="Kata Sandi Lama">

								<span class="help-block"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Kata Sandi Baru</label>

							<div class="col-sm-8">
								<input type="password" class="form-control" id="new_password" name="new_password" placeholder="Kata Sandi Baru">

								<span class="help-block"></span>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-4 control-label">Konfirmasi Kata Sandi Baru</label>

							<div class="col-sm-8">
								<input type="password" class="form-control" id="new_password_confirm" name="new_password_confirm" placeholder="Konfirmasi Kata Sandi Baru">

								<span class="help-block"></span>
							</div>
						</div>
						
						<div class="form-group">
		                    <div class="col-sm-offset-3 col-sm-9">
		                      	<button type="submit" class="btn btn-danger"><i class="fa fa-save"></i> Simpan</button>
		                    </div>
	                  	</div>
					</form>
				</div>
			</div>

			<div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Ubah Foto Profile</h3>
                </div>
				<div class="box-body">
					<form class="form-horizontal" method="POST" id="formAvatar">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Foto Profile</label>
                            
                            <div class="col-sm-9 fileinput fileinput-new" data-provides="fileinput">
                                <div class="fileinput-new thumbnail" style="width: 200px; height: 150px;">
                                    <img id="img-upload" src="{{ asset('images/avatar_owner.png') }}" style="width: 200px; height: 150px;">
                                </div>
                                <div class="fileinput-preview fileinput-exists thumbnail" style="max-width: 200px; max-height: 150px;"></div>
                                <div>
                                    <span class="btn btn-default btn-file">
                                        <span class="fileinput-new">Select Image</span>
                                        <span class="fileinput-exists">Change</span>
                                        <input type="file" id="file_id" name="file_id">
                                    </span>
                                    <a href="#" class="btn btn-default fileinput-exists" data-dismiss="fileinput">
                                        Remove
                                    </a>
                                </div>
                                <span class="help-block"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-3 col-sm-9">
                                <button type="submit" class="btn btn-danger"><i class="fa fa-save"></i> Simpan</button>
                            </div>
                        </div>
                    </form>
				</div>
			</div>
		</div>
	</div>
@endsection

@section('css')
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/css/jasny-bootstrap.min.css">
@endsection

@section('js')
    <script src="//cdnjs.cloudflare.com/ajax/libs/jasny-bootstrap/3.1.3/js/jasny-bootstrap.min.js"></script>
	
    <script type="text/javascript">
        jQuery(document).ready(function($){
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

            $('#formSetting #name').val("{{ $data->name }}");
            $('#formSetting #phone').val("{{ $data->phone }}");
            $('#formSetting #address').val("{{ $data->address }}");
            $('#formSetting #birthplace').val("{{ $data->birthplace }}");
            $('#formSetting #birthdate').val("{{ $data->birthdate }}");
            $('#formSetting #age').val("{{ $data->age }}");
            $('#formSetting #religion').val("{{ $data->religion }}");
            
            if( "{{ $data->gender }}" === "Male" )
                $('#gender_lk').prop("checked", true);
            else
                $('#gender_pr').prop("checked", true);

            @if ( $data->file_id != null )
                $('#formAvatar #img-upload').attr('src', "{{ asset('storage/'. $data->file->path)}}");
            @endif

            // Submit Form setting
            $('#formSetting').submit(function (event) {
                event.preventDefault();
                $('#formSetting button[type=submit]').button('loading');
                $('#formSetting div.form-group').removeClass('has-error');
                $('#formSetting .help-block').empty();

                var _data = $("#formSetting").serialize();

                $.ajax({
                    url: "{{ route('owner.profile.setting', ['id' => $data->id]) }}",
                    method: 'POST',
                    data: _data,
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
                        } else {
                            $.toast({
                                heading: 'Error',
                                text : response.message,
                                position : 'top-right',
                                allowToastClose : true,
                                showHideTransition : 'fade',
                                icon : 'error',
                                loader : false,
                                hideAfter: 5000
                            });
                        }

                        $('#formSetting')[0].reset();
                        $('#formSetting button[type=submit]').button('reset');
                    },
                    error: function(response){
                        if (response.status === 422) {
                            // form validation errors fired up
                            var error = response.responseJSON.errors;
                            var data = $('#formSetting').serializeArray();
                            $.each(data, function(key, value){
                                if( error[data[key].name] != undefined ){
                                    var elem;
                                    if( $("#formSetting input[name='" + data[key].name + "']").length )
                                        elem = $("#formSetting input[name='" + data[key].name + "']");
                                    else if( $("#formSetting textarea[name='" + data[key].name + "']").length )
                                        elem = $("#formSetting textarea[name='" + data[key].name + "']");
                                    else
                                        elem = $("#formSetting select[name='" + data[key].name + "']");
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
                        $('#formSetting button[type=submit]').button('reset');
                    }
                });
            });

            $('#formPassword').submit(function (event) {
                event.preventDefault();
                $('#formPassword button[type=submit]').button('loading');
                $('#formPassword div.form-group').removeClass('has-error');
                $('#formPassword .help-block').empty();

                var _data = $("#formPassword").serialize();

                $.ajax({
                    url: "{{ route('owner.profile.password', ['id' => $data->id]) }}",
                    method: 'POST',
                    data: _data,
                    cache: false,

                    success: function (response) {
                        if ( response.success ) {
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

                            $('#formPassword')[0].reset();
                        }
                        else{
                            $.toast({
                                heading: 'Error',
                                text : response.message,
                                position : 'top-right',
                                allowToastClose : true,
                                showHideTransition : 'fade',
                                icon : 'error',
                                loader : false,
                                hideAfter: 5000
                            });
                        }

                        $('#formPassword button[type=submit]').button('reset');
                    },

                        error: function(response){
                        if (response.status === 422) {
                            // form validation errors fired up
                            var error = response.responseJSON.errors;
                            var data = $('#formPassword').serializeArray();
                            $.each(data, function(key, value){
                                if( error[data[key].name] != undefined ){
                                    var elem;
                                    if( $("#formPassword input[name='" + data[key].name + "']").length )
                                        elem = $("#formPassword input[name='" + data[key].name + "']");
                                    else if( $("#formPassword textarea[name='" + data[key].name + "']").length )
                                        elem = $("#formPassword textarea[name='" + data[key].name + "']");
                                    else
                                        elem = $("#formPassword select[name='" + data[key].name + "']");
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
                        $('#formPassword button[type=submit]').button('reset');
                    }
                });
            });

            $('#formAvatar').submit(function (event) {
                event.preventDefault();
                $('#formAvatar button[type=submit]').button('loading');
                $('#formAvatar div.form-group').removeClass('has-error');
                $('#formAvatar .help-block').empty();

                var formData = new FormData($("#formAvatar")[0]);

                $.ajax({
                    url: "{{ route('owner.profile.avatar', ['id' => $data->id]) }}",
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
                        $('#formAvatar button[type=submit]').button('reset');
                    },

                    error: function(response){
                        if (response.status === 422) {
                            // form validation errors fired up
                            var error = response.responseJSON.errors;
                            if(error['file_id'] != undefined){
                                $("#formAvatar input[name='file_id']").parent().parent().parent().find('.help-block').text(error['file_id']);
                                $("#formAvatar input[name='file_id']").parent().parent().parent().find('.help-block').show();
                                $("#formAvatar input[name='file_id']").parent().parent().parent().parent().addClass('has-error');
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
                        $('#formAvatar button[type=submit]').button('reset');
                    }
                });
            });

            //Date picker
            $('#birthdate').datepicker({
                autoclose: true,
                format: 'yyyy-mm-dd',
                endDate: '+1d',
                datesDisabled: '+1d',
            });
        });
    </script>
@endsection