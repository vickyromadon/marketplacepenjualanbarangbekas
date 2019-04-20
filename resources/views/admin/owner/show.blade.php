@extends('layouts.admin')

@section('header')
	<h1>
		Detail Owner
	</h1>
	<ol class="breadcrumb">
		<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
		<li>Owner</li>
		<li class="active">Detail Owner</li>
	</ol>
@endsection

@section('content')
	<div class="row">
		<div class="col-md-4">
			<div class="box box-success">
				<div class="box-header">
					<h3 class="box-title">Profile Owner</h3>
				</div>
				<div class="box-body box-profile">
					@if ( $data->file_id != null )
						<img class="profile-user-img img-responsive" src="{{ asset('storage/'.$data->file->path) }}" alt="User profile picture" style="width: 150px; height: 150px;">
					@else
						<img class="profile-user-img img-responsive" src="{{ asset('images/avatar_owner.png') }}" alt="User profile picture" style="width: 150px; height: 150px;">
					@endif

					<h3 class="profile-username text-center">{{ $data->name }}</h3>

					<ul class="list-group list-group-unbordered">
						<li class="list-group-item">
							<b>Email</b> <a class="pull-right">{{ $data->email }}</a>
						</li>
						<li class="list-group-item">
							<b>Phone</b>
							<a class="pull-right">
								@if ( $data->phone )
									{{ $data->phone }}
								@else
									-
								@endif
							</a>
						</li>
						<li class="list-group-item">
							<b>Register Date</b>
							<a class="pull-right">
								@if ( $data->created_at )
									{!! date('d F Y', strtotime($data->created_at)); !!}
								@else
									-
								@endif
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>

		<div class="col-md-8">
			<div class="box box-success">
				<div class="box-header">
					<h3 class="box-title">Biodata Owner</h3>
				</div>
				<div class="box-body box-profile">
					<ul class="list-group list-group-unbordered">
						<li class="list-group-item">
							<b>Birthdate : </b>
							<a>
								@if ( $data->birthdate != null )
									{!! date('d F Y', strtotime($data->birthdate)); !!}
								@else
									-
								@endif
							</a>
						</li>
						<li class="list-group-item">
							<b>Birthplace : </b>
							<a>
								@if ( $data->birthplace != null )
									{{ $data->birthplace }}
								@else
									-
								@endif
							</a>
						</li>
						<li class="list-group-item">
							<b>Age : </b>
							<a>
								@if ( $data->age != null )
									{{ $data->age }}
								@else
									-
								@endif
							</a>
						</li>
						<li class="list-group-item">
							<b>Religion : </b> 
							<a>
								@if ( $data->religion != null )
									{{ $data->religion }}
								@else
									-
								@endif
							</a>
						</li>
						<li class="list-group-item">
							<b>Gender : </b> 
							<a>
								@if ( $data->gender != null )
									{{ $data->gender }}
								@else
									-
								@endif
							</a>
						</li>
						<li class="list-group-item">
							<b>Address : </b>
							<a>
								@if ( $data->address != null )
									{{ $data->address }}
								@else
									-
								@endif
							</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	
@endsection